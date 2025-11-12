<?php

namespace App\Http\Middleware;

use App\Enums\ErrorCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Role required for the route
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                    'success' => false,
                    'error_code' => ErrorCode::UNAUTHENTICATED,
                    'message' => 'You are Unauthorized.',
                ], 401);
        }

        // Check if user has role
        if (!$user->roles->contains('name', $role)) {
            return response()->json([
                    'success' => false,
                    'error_code' => ErrorCode::FORBIDDEN,
                    'message' => 'Forbidden. You do not have the required role.',
                ], 403);
        }

        return $next($request);
    }
}
