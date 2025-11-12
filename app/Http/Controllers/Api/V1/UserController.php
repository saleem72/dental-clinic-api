<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints related to authenticated user's profile"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"User"},
     *     summary="Get authenticated user's profile",
     *     operationId="getProfile",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Returns the authenticated user's profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/UserResource"
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
    public function getProfile(Request $request)
    {
        $user = $request->user();
        return apiResponse(new UserResource($user));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/update-profile",
     *     tags={"User"},
     *     summary="Update authenticated user's profile",
     *     operationId="updateProfile",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+123456789"),
     *             @OA\Property(
     *                 property="image",
     *                 type="string",
     *                 format="binary",
     *                 description="Optional. Upload a new avatar image or pass null to remove existing."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/UserResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed."),
     *             @OA\Property(property="validation_errors", type="object")
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
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Handle new image upload and deletion of old image
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('avatars', 'public');
            $validatedData['image'] = $path;
        }
        // Handle explicit removal of existing image
        elseif (array_key_exists('image', $validatedData) && is_null($validatedData['image'])) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $validatedData['image'] = null;
        }

        $user->update($validatedData);

        return apiResponse(data: new UserResource($user), message: 'Updated successfully');
    }
}
