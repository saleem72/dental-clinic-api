<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentProcedureResource extends JsonResource
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
            "session_id" => $this->treatment_session_id,
            "dentist_id" => $this->dentist_id,
            "dental_procedure_id" => $this->dental_procedure_id,
            "tooth_code" => $this->tooth_code,
            "performed_at" => $this->performed_at,
            "cost" => $this->cost,
            "notes" => $this->notes,
            // relations
            "treatment" => $this->whenLoaded('treatmentCourse', fn() => new TreatmentMiniResource($this->treatmentCourse)) ,
            "session" => $this->whenLoaded('session', fn() => new TreatmentSessionMiniResource($this->session)) ,
            "procedure" => $this->whenLoaded('dentalProcedure', fn() => new DentalProcedureResource($this->dentalProcedure)) ,
            "dentist" => $this->whenLoaded('dentist', fn() => new DentistMiniResource($this->dentist)) ,
        ];
    }
}
