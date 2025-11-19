<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentProcedureMiniResource extends JsonResource
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
            "tooth_code" => $this->tooth_code,
            "performed_at" => $this->performed_at,
            "cost" => $this->cost,
            "notes" => $this->notes,
        ];
    }
}
