<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (User::find(auth()->user()->id)->hasRole('vendor')) {
                    return redirect(RouteServiceProvider::VENDOR_HOME);
                } else if (User::find(auth()->user()->id)->hasRole('customer')) {
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    $user = Auth::user();
                    $permissions = ['cashier-play-area-management', 'cashier-events-management', 'cashier-items-management'];
                    $hasOnlySpecifiedPermissions = $user->roles()->first()->permissions->pluck('name')->diff($permissions)->isEmpty();
                    if ($user->hasAnyPermission($permissions) && $hasOnlySpecifiedPermissions) {
                        return redirect('/dashboard/orders/create');
                    }
                    return redirect(RouteServiceProvider::DASHBOARD_HOME);
                }
            }
        }

        return $next($request);
    }
}
