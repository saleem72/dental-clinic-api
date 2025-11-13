<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class TreatmentProcedure extends Model
{
    protected $fillable = [
        'treatment_id', 'treatment_session_id', 'dental_procedure_id',
        'tooth_code', 'fee'
    ];

    public function treatmentCourse() {
        return $this->belongsTo(TreatmentCourse::class, 'treatment_id');
    }

    public function session() {
        return $this->belongsTo(TreatmentSession::class, 'treatment_session_id');
    }

    public function dentalProcedure() {
        return $this->belongsTo(DentalProcedure::class);
    }
}
