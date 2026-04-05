<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Cache;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next, string $permission = null)
    {
        $startTime = microtime(true);

        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return $this->unauthorizedResponse('API token tidak ditemukan. Gunakan header: Authorization: Bearer {token}');
        }

        $hashedToken = hash('sha256', $bearerToken);

        $token = ApiToken::where('token', $hashedToken)->first();

        if (!$token) {
            return $this->unauthorizedResponse('API token tidak valid.');
        }

        if (!$token->isValid()) {
            return $this->unauthorizedResponse('API token sudah expired atau nonaktif.');
        }

        if (!$token->isIpAllowed($request->ip())) {
            return $this->forbiddenResponse('IP address tidak diizinkan.');
        }

        // Rate limiting
        $rateLimitKey = 'api_rate:' . $token->id_api_token;
        $attempts = (int) Cache::get($rateLimitKey, 0);

        if ($attempts >= $token->rate_limit) {
            $this->logRequest($token, $request, 429, $startTime);
            return response()->json([
                'success' => false,
                'message' => 'Rate limit tercapai. Coba lagi nanti.',
            ], 429);
        }

        Cache::put($rateLimitKey, $attempts + 1, 60);

        // Permission check
        if ($permission && !$token->hasPermission($permission)) {
            $this->logRequest($token, $request, 403, $startTime);
            return $this->forbiddenResponse('Token tidak memiliki permission: ' . $permission);
        }

        $token->recordUsage();

        $request->attributes->set('api_token', $token);

        $response = $next($request);

        $this->logRequest($token, $request, $response->getStatusCode(), $startTime);

        return $response;
    }

    private function unauthorizedResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    private function forbiddenResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }

    private function logRequest(?ApiToken $token, Request $request, int $statusCode, float $startTime): void
    {
        $responseTime = (int) ((microtime(true) - $startTime) * 1000);

        try {
            ApiLog::create([
                'id_api_token'   => $token?->id_api_token,
                'endpoint'       => $request->path(),
                'method'         => $request->method(),
                'ip_address'     => $request->ip(),
                'response_code'  => $statusCode,
                'response_time_ms' => $responseTime,
            ]);
        } catch (\Exception $e) {
            // Jangan biarkan logging error mengganggu response
            \Log::warning('API log failed: ' . $e->getMessage());
        }
    }
}
