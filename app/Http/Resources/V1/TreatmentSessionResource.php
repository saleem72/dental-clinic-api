<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentSessionResource extends JsonResource
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
            "treatment_id" => $this->treatment_course_id,
            "dentist_id" => $this->dentist_id,
            "start_at" => $this->start_at,
            "estimated_time" => $this->estimated_time,
            "notes" => $this->notes,
            "status" => $this->status,
            "procedures" => $this->whenLoaded(
                'procedures',
                fn() => TreatmentProcedureMiniResource::collection($this->procedures)
            ),
            "dentist" => $this->whenLoaded(
                'dentist',
                fn() => new DentistMiniResource($this->dentist)
            ),
            "treatment_course" => $this->whenLoaded(
                'treatmentCourse',
                fn() => new TreatmentMiniResource($this->treatmentCourse)
            ),
        ];
    }
}
