<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            // If user is admin and trying to access admin login, redirect to admin dashboard
            if ($user->isAdmin() && $user->isActive() && $request->is('admin/login')) {
                return redirect('/admin/dashboard');
            }

            // For other cases, redirect to home
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
