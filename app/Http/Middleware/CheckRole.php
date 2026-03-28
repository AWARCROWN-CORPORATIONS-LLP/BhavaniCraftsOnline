<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect('/login');
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        // Check for any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Tiered Redirection Logic: If unauthorized for this specific tier, send to primary portal
        if ($user->hasRole('super_admin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('admin') || $user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        } elseif ($user->hasRole('logistics')) {
            return redirect()->route('logistics.dashboard');
        } elseif ($user->hasRole('franchise')) {
            return redirect()->route('franchise.dashboard');
        } elseif ($user->hasRole('poojari')) {
            return redirect()->route('poojari.dashboard');
        }

        abort(403, 'Unauthorized access for your tier.');
    }
}
