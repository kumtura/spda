<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectAuthenticatedFromPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $level = Session::get('level');
            
            // Level 3 = Unit Usaha -> redirect to usaha home
            if ($level == '3') {
                return redirect('/administrator/usaha/home');
            }
            
            // Level 2 = Kelian Banjar -> redirect to kelian home
            if ($level == '2') {
                return redirect('/administrator/home');
            }
            
            // Level 1 = Bendesa Adat -> allow access to public pages
            // Level 4 = Admin Sistem -> allow access to public pages
        }

        return $next($request);
    }
}
