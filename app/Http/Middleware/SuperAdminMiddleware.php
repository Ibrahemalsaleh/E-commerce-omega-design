<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request - only super admins allowed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is a super admin
        if (Auth::check() && Auth::user()->is_super_admin) {
            return $next($request);
        }
        
        // Redirect to admin dashboard if not a super admin
        return redirect()->route('admin.dashboard')
            ->with('error', 'This action requires super admin privileges.');
    }
}