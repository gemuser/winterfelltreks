<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        // This is a placeholder for Inertia middleware
        // We're not using Inertia, but Laravel expects this
        return $next($request);
    }
}