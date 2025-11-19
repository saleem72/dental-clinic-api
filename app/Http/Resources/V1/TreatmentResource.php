<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "patient_id" => $this->patient_id,
            "dentist_id" => $this->dentist_id,
            "started_at" => $this->started_at,
            "completed_at" => $this->completed_at,
            "notes" => $this->notes,
            "status" => $this->status,
            "patient" => $this->whenLoaded('patient', fn () => new PatientMiniResource($this->patient)),
            "dentist" => $this->whenLoaded('dentist', fn () => new DentistMiniResource($this->dentist)),
        ];
    }
}
