<?php

namespace App\Models\V1;

use App\Enums\TreatmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Decimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'dentist_id', 'started_at', 'completed_at',
        'notes', 'total_cost', 'status'
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime:Y-m-d\TH:i:s\Z',
            'completed_at' => 'datetime:Y-m-d\TH:i:s\Z',
            'type' => TreatmentStatus::class,
            'total_cost' => 'decimal:2',
        ];
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function dentist() {
        return $this->belongsTo(Dentist::class);
    }

    public function treatmentSessions() {
        return $this->hasMany(TreatmentSession::class, 'treatment_course_id');
    }

    public function procedures() {
        return $this->hasMany(TreatmentProcedure::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
