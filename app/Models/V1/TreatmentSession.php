<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class TreatmentSession extends Model
{
    protected $fillable = ['treatment_id', 'date', 'status', 'notes'];

    public function treatmentCourse() {
        return $this->belongsTo(TreatmentCourse::class, 'treatment_id');
    }
}
