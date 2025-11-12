<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PatientResource",
 *     title="Patient",
 *     description="Patient details returned by the API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="username", type="string", example="john_doe"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="image", type="string", example="http://localhost:8000/storage/avatars/john.png"),
 *     @OA\Property(property="phone", type="string", example="+123456789"),
 *     @OA\Property(property="date_of_birth", type="string", example="1972-11-28"),
 *     @OA\Property(property="gender", type="enum[male, female, other]", example="male"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         @OA\Items(type="string", example="dentist")
 *     ),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="must_change_password", type="boolean", example=false)
 * )
 */
class PatientResource extends JsonResource
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
            'name' => $this->user->name,
            'phone' => $this->user->phone,
            'email' => $this->user->email,
            'user_id' => $this->user_id,
            'date_of_birth' => optional($this->date_of_birth)->format('Y-m-d'),
            'gender' => $this->gender,
            'medical_notes' => $this->medical_notes,
            'medical_history' => $this->medical_history,
            'dentist_id' => $this->dentist_id,
            'patient_code' => $this->patient_code,

            // 'user' => $this->whenLoaded('user', fn () => new UserMiniResource($this->user)),
            'dentist' => $this->whenLoaded('dentist', fn () => new DentistResource($this->dentist)),
            // 'created_by' => new UserMiniResource($this->user->creator()),

        ];
    }
}
