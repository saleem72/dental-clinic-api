<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class UserMiniResource extends JsonResource
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
            ? url(Storage::url($this->image))
            : url(Config::get('app.default_avatar_path'));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' => $avatarUrl,
        ];
    }
}
