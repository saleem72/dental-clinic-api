<?php

namespace App\Http\Middleware;

use App\Enums\ErrorCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                    'success' => false,
                    'error_code' => ErrorCode::UNAUTHENTICATED,
                    'message' => 'You are Unauthorized.',
                ], 401);
        }

        if ($user->must_change_password) {
            return response()->json([
                    'success' => false,
                    'error_code' => ErrorCode::USER_HAS_To_CHANGE_PASSWORD,
                    'message' => 'You have to change your password before',
                ], 400);
        }

        return $next($request);
    }
}
