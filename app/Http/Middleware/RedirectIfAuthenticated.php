<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($request->routeIs('password.request', 'password.email', 'password.reset', 'password.store')) {
            return $next($request);
        }

        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            if (($user?->role ?? null) === 'petugas') {
                return redirect()->route('petugas.dashboard');
            }

            if (($user?->role ?? null) === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user?->approval_status !== 'approved') {
                return redirect()->route('account.pending');
            }

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
