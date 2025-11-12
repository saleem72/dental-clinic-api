<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DentistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'license_number' => $this->license_number,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'commission_rate' => $this->commission_rate,
            'is_available' => $this->is_available,

            'user' => $this->whenLoaded('user', fn () => new UserMiniResource($this->user)),

            'patients_count' => $this->whenCounted('patients'),
            'patients' => $this->whenLoaded('patients', fn () => PatientResource::collection($this->patients)),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
