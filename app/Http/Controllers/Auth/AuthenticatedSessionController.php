<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $status = "Guest";

        if ($user->id_level == config('myconfig.level.bendesa')) {
            $status = config('myconfig.roles.1');
        } elseif ($user->id_level == config('myconfig.level.kelian')) {
            $status = config('myconfig.roles.2');
        } elseif ($user->id_level == config('myconfig.level.usaha')) {
            $status = config('myconfig.roles.3');
        }

        $request->session()->put([
            'namapt' => $user->name,
            'email' => $user->email,
            'status' => $status,
            'level' => $user->id_level,
            'idloginpt' => $user->id,
            'boolsessionpt' => 1,
        ]);

        // Redirect based on user level
        if ($user->id_level == config('myconfig.level.usaha')) {
            return redirect('/administrator/usaha/home');
        }

        return redirect()->intended(route('administrator.home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
