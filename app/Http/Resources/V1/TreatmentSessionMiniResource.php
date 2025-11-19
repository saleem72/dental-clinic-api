<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentSessionMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id  ,
            "start_at" => $this->start_at  ,
            "estimated_time" => $this->estimated_time  ,
            "notes" => $this->notes  ,
            "status" => $this->status  ,
        ];
    }
}
