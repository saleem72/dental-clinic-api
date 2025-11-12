<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User details returned by the API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="john_doe"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="image", type="string", example="http://localhost:8000/storage/avatars/john.png"),
 *     @OA\Property(property="phone", type="string", example="+123456789"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         @OA\Items(type="string", example="dentist")
 *     ),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="must_change_password", type="boolean", example=false)
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //   $avatarUrl = $this->image
        //     ? Storage::disk('public')->url($this->image)
        //     : Storage::disk('public')->url(Config::get('app.default_avatar_path'));

        $avatarUrl = $this->image
            ?  url(Storage::url($this->image))
            : url(Config::get('app.default_avatar_path'));

        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $avatarUrl,
            'phone' => $this->phone,
            'roles' => $this->roles->pluck('name'), // RoleResource::collection($this->roles),
            'is_active' => $this->is_active,
            'must_change_password' => $this->must_change_password,
        ];
    }
}
