<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check() || !in_array(Auth::user()->id_level, $levels)) {
            return redirect('/login');
        }

        return $next($request);
    }
}
