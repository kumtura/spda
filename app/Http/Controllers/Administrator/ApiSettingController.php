<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\ApiLog;
use Illuminate\Support\Str;

class ApiSettingController extends Controller
{
    public function index()
    {
        $tokens = ApiToken::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        $permissions = ApiToken::availablePermissions();

        // Statistik API usage 7 hari terakhir
        $recentLogs = ApiLog::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total_request')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('admin.pages.settings.api', compact('tokens', 'permissions', 'recentLogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'permissions' => 'required|array|min:1',
            'ip_whitelist' => 'nullable|string|max:500',
            'rate_limit'  => 'nullable|integer|min:1|max:10000',
            'expires_at'  => 'nullable|date|after:today',
        ]);

        // Generate plain token
        $plainToken = Str::random(64);
        $hashedToken = hash('sha256', $plainToken);

        $token = ApiToken::create([
            'name'         => $request->name,
            'token'        => $hashedToken,
            'permissions'  => $request->permissions,
            'ip_whitelist' => $request->ip_whitelist,
            'rate_limit'   => $request->input('rate_limit', 100),
            'expires_at'   => $request->expires_at,
            'created_by'   => auth()->id(),
            'aktif'        => true,
        ]);

        // Token hanya ditampilkan sekali ini
        return redirect()->back()->with([
            'success'    => 'API Token berhasil dibuat! Salin token di bawah, token hanya ditampilkan sekali.',
            'new_token'  => $plainToken,
            'token_name' => $token->name,
        ]);
    }

    public function toggle(Request $request, $id)
    {
        $token = ApiToken::findOrFail($id);
        $token->update(['aktif' => !$token->aktif]);

        $status = $token->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Token '{$token->name}' berhasil {$status}.");
    }

    public function destroy($id)
    {
        $token = ApiToken::findOrFail($id);
        $name = $token->name;
        $token->delete();

        return redirect()->back()->with('success', "Token '{$name}' berhasil dihapus.");
    }

    public function update(Request $request, $id)
    {
        $token = ApiToken::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100',
            'permissions' => 'required|array|min:1',
            'ip_whitelist' => 'nullable|string|max:500',
            'rate_limit'  => 'nullable|integer|min:1|max:10000',
            'expires_at'  => 'nullable|date',
        ]);

        $token->update([
            'name'         => $request->name,
            'permissions'  => $request->permissions,
            'ip_whitelist' => $request->ip_whitelist,
            'rate_limit'   => $request->input('rate_limit', 100),
            'expires_at'   => $request->expires_at,
        ]);

        return redirect()->back()->with('success', "Token '{$token->name}' berhasil diperbarui.");
    }

    public function logs(Request $request)
    {
        $query = ApiLog::with('apiToken')
            ->orderBy('created_at', 'desc');

        if ($request->filled('id_api_token')) {
            $query->where('id_api_token', $request->id_api_token);
        }

        $logs = $query->paginate(50);
        $tokens = ApiToken::all();

        return view('admin.pages.settings.api_logs', compact('logs', 'tokens'));
    }
}
