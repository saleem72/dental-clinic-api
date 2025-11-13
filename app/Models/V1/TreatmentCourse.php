<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class TreatmentCourse extends Model
{
    protected $fillable = [
        'patient_id', 'dentist_id', 'started_at', 'completed_at',
        'notes', 'total_cost', 'status'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function dentist() {
        return $this->belongsTo(Dentist::class);
    }

    public function sessions() {
        return $this->hasMany(TreatmentSession::class, 'treatment_id');
    }

    public function procedures() {
        return $this->hasMany(TreatmentProcedure::class, 'treatment_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
