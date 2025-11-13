<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreatePatientRequest;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\V1\CreateUserRequest;
use App\Http\Requests\V1\ResetUserPasswordRequest;
use App\Http\Requests\V1\SetDefaultAvatarRequest;
use App\Http\Resources\V1\PatientResource;
use App\Http\Resources\V1\RoleResource;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\Role;
use App\Models\V1\User;
use App\Services\CreatePatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints related to users management such as create user, or deactivate him"
 * )
 */
class UsersController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/roles",
     *     tags={"Users"},
     *     summary="Get a list of all roles",
     *     operationId="getRoles",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Roles retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function roles() {
        $roles = Role::all();
        return apiResponse(RoleResource::collection($roles));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Get a list of all users in the system",
     *     operationId="getUsers",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully")
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
    public function index(Request $request) {

        // Define a whitelist of relationships that are allowed to be included
        $allowedIncludes = [
            'patient',
            'roles',
            'dentist'
        ];

        $query = User::query();
        $requestedIncludes = [];

        // 1. Check if the 'include' query parameter is present
        if ($request->has('include')) {
            // Get the relations as an array from the request
            $relationsFromRequest = explode(',', $request->input('include'));

            // 2. Filter the requested relations against the whitelist
            $requestedIncludes = array_filter($relationsFromRequest, function ($relation) use ($allowedIncludes) {
                return in_array($relation, $allowedIncludes);
            });
        }

        // 3. Eager load only the *whitelisted* and *requested* relations
        if (!empty($requestedIncludes)) {
            $query->with($requestedIncludes);
        }

        $users = $query->get();
        return apiResponse(UserResource::collection($users));
    }



    /**
     * @OA\Patch(
     *     path="/api/v1/users/toggle-user-activity/{user}",
     *     tags={"Users"},
     *     summary="Toggle the active status of a user",
     *     operationId="toggleUserActivity",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user to toggle activity",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Not implemented",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="you have not implemented it yet"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function toggleUserActivity(Request $request, User $user) {
        $user->toggleActivity();

        $name = $user->name;
        $isActive = $user->is_active ? 'true' : 'false';
        return apiResponse(
            message: "active status for user {$name} is {$isActive}",
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/reset-user-password/{user}",
     *     tags={"Users"},
     *     summary="Reset a user's password to the default",
     *     operationId="resetUserPassword",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user whose password will be reset",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password for user John Doe has changed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function resetUserPassword(Request $request, User $user) {
        $user->resetPassword('password123');

        $name = $user->name;
        return apiResponse(
            message: "Password for user {$name} has changed successfully",
        );
    }


    /**
     * Updates the application's global default avatar image file.
     *
     * This function handles the process of replacing the existing default avatar file
     * with a new one provided in the request. It ensures the new file uses the
     * configured default filename, cleans up the old file, and returns the
     * accessible URL of the new default avatar.
     *
     * @param SetDefaultAvatarRequest $request The validated request object containing the new image file.
     * @return JsonResponse Returns a JSON response with the absolute URL of the new default avatar image.
     * @throws Exception If file storage or deletion fails.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/users/set-default-avatar",
     *     tags={"Users"},
     *     summary="Update the global default avatar image",
     *     operationId="setDefaultAvatar",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"image"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="The new default avatar image file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Global default avatar updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Global default avatar updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="default_path", type="string", example="http://localhost:8000/storage/avatars/default.png")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function setDefaultAvatar(SetDefaultAvatarRequest $request)
    {
        // Retrieve configured filename and directory path (e.g., 'default.png', 'avatars')
        $defaultFilename = basename(Config::get('app.default_avatar_path'));
        $directory = dirname(Config::get('app.default_avatar_path'));
        $fullPath = Config::get('app.default_avatar_path');

        // Delete the previous default image file from public storage if it exists
        if (Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->delete($fullPath);
        }

        // Store the newly uploaded image using the exact configured filename
        // $path variable now holds the relative path like 'avatars/default.png'
        $path = $request->file('image')->storeAs($directory, $defaultFilename, 'public');

        // Generate the full absolute URL for the response data
        return apiResponse(
            data: ['default_path' => url(Storage::url($path))],
            message: 'Global default avatar updated successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/user-by-id/userId",
     *     tags={"Users"},
     *     summary="Get a user with this userId in the system",
     *     operationId="userById",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Single user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/UserResource"
     *             ),
     *             @OA\Property(property="message", type="string", example="User retrieved successfully")
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
    public function userById(User $user)  {
        return apiResponse(new UserResource($user));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/user-by-username/username",
     *     tags={"Users"},
     *     summary="Get a user with this username in the system",
     *     operationId="userByUsername",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Single user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/UserResource"
     *             ),
     *             @OA\Property(property="message", type="string", example="User retrieved successfully")
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
    public function userByUsername(string $username)
    {
        try {
            $user = User::where('username', $username)->firstOrFail();
            return apiResponse(data: new UserResource($user), success: true);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return apiResponse(
                success: false,
                message: "User with username '{$username}' not found.",
                status: 404,
            );
        }
    }


}
