<?php

namespace App\DTOs;

use App\Models\Schedule;
use App\Models\V1\TreatmentSession;

class DentistAppointmentDto
{
    public int $id;
    public string $start_at;
    public int $duration;
    public string $status;

    public ?int $patient_id;
    public ?string $patient_name;
    public ?string $patient_code;

    public ?string $notes;

    public function __construct(TreatmentSession  $s)
    {
        $this->id           = $s->id;
        $this->start_at     = $s->start_at;
        $this->duration     = $s->estimated_time;
        $this->status       = $s->status;

        $this->patient_id   = $s->patient->id ?? null;
        $this->patient_name = $s->patient->user->name ?? null;
        $this->patient_code = $s->patient->patient_code ?? null;

        $this->notes        = $s->notes;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'start_at'  => $this->start_at,
            'duration'  => $this->duration,
            'status'    => $this->status,
            'patient'   => [
                'id'           => $this->patient_id,
                'name'         => $this->patient_name,
                'patient_code' => $this->patient_code,
            ],
            'notes'     => $this->notes,
        ];
    }
}
