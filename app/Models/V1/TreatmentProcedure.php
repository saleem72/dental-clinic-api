<?php

namespace App\Models\V1;

use App\Enums\Tooth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_course_id',
        'treatment_session_id',
        'dental_procedure_id',
        'dentist_id',
        'tooth_code',
        'cost',
        'performed_at',
        'notes'
    ];

    protected $casts = [
        'performed_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'tooth_code' => Tooth::class,
    ];

    public function treatmentCourse()
    {
        return $this->belongsTo(TreatmentCourse::class, 'treatment_course_id');
    }

    public function session()
    {
        return $this->belongsTo(TreatmentSession::class, 'treatment_session_id');
    }

    public function dentalProcedure()
    {
        return $this->belongsTo(DentalProcedure::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }
}


