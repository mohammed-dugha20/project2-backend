<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FinishingCompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user = Auth::user();
        
        if (!$user->isFinishingCompany()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only finishing companies can access this resource.'
            ], 403);
        }

        // Check if the company is active
        if (!$user->finishingCompany->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your company account is currently inactive. Please contact the platform administrator.'
            ], 403);
        }

        return $next($request);
    }
}
