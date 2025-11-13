<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'patient_id', 'treatment_course_id', 'amount', 'date', 'notes'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentCourse() {
        return $this->belongsTo(TreatmentCourse::class);
    }
}
