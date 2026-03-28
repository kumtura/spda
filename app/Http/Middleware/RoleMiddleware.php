<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $level
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        \Log::info('Role Check', [
            'user' => Auth::user()?->id_level,
            'required' => $levels,
            'url' => $request->fullUrl()
        ]);

        if (!Auth::check() || !in_array(Auth::user()->id_level, $levels)) {
            \Log::warning('Role Denied', [
                'user' => Auth::user()?->id_level,
                'required' => $levels
            ]);
            return redirect('/login');
        }

        return $next($request);
    }
}
