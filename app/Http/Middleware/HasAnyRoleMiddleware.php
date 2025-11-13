<?php

namespace App\Http\Middleware;

use App\Enums\ErrorCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $rolesString  A pipe-separated string of roles (e.g., 'manager|dentist|receptionist')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $rolesString = ''): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You are Unauthorized.',
            ], 401);
        }

        // Split the pipe-separated string into an array of roles
        $roles = explode('|', $rolesString);

        // Check if the user has at least one of the specified roles
        $userRoles = $user->roles->pluck('name');
        $hasRole = $userRoles->intersect($roles)->isNotEmpty();

        if (!$hasRole) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not have the required role(s).',
            ], 403);
        }

        return $next($request);
    }
}
