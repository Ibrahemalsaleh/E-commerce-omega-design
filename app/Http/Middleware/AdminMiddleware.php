<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is an admin or super admin
        if (Auth::check() && (Auth::user()->is_admin || Auth::user()->is_super_admin)) {
            return $next($request);
        }
        
        // Redirect to home if not an admin
        return redirect()->route('home')->with('error', 'You do not have admin access.');
    }
}