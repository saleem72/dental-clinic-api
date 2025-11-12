<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints related to user authentication and password management"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Login with username and password",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="john_doe"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="LOGIN SUCCESS"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     ref="#/components/schemas/UserResource",
     *                     description="Authenticated user details"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="1|abc123xyz..."
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="invalid credentials"),
     *             @OA\Property(property="error_code", type="integer", example=1001)
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return apiResponse(
                success: false,
                message: 'invalid credentials',
                error_code: ErrorCode::INVALID_Credentials,
            );
        }

        if (!$user->is_active) {
            return apiResponse(
                success: false,
                message: 'you are temporally block, check with manager',
                error_code: ErrorCode::USER_Temporally_BLOCKED,
            );
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return apiResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            success: true,
            message: 'LOGIN SUCCESS'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="Logout the authenticated user",
     *     operationId="logout",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully logged out.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return apiResponse(message: 'Successfully logged out.');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/change-password",
     *     tags={"Authentication"},
     *     summary="Change the authenticated user's password",
     *     operationId="changePassword",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password", "new_password"},
     *             @OA\Property(property="old_password", type="string", example="123456"),
     *             @OA\Property(property="new_password", type="string", example="654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="You have changed your password successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Wrong old password",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="you enter the wrong password"),
     *             @OA\Property(property="error_code", type="integer", example=1002)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;

        $passwordMatch = Hash::check($oldPassword, $user->password);

        if (!$passwordMatch) {
            return apiResponse(
                success: false,
                message: 'you enter the wrong password',
                error_code: ErrorCode::WRONG_PASSWORD,
                status: 400,
            );
        }

        $user->password = Hash::make($newPassword);
        $user->must_change_password = false;
        $user->save();

        return apiResponse(message: 'You have changed your password successfully');
    }
}
