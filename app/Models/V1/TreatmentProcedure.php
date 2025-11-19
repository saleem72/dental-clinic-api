<?php

namespace App\Models\V1;

use App\Enums\Tooth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'treatment_session_id',
        'dental_procedure_id',
        'dentist_id',
        'tooth_code',
        'cost',
    ];

    protected $casts = [
        'tooth_code' => Tooth::class,
    ];

    public function treatmentCourse()
    {
        return $this->belongsTo(TreatmentCourse::class, 'treatment_id');
    }

    public function session()
    {
        return $this->belongsTo(TreatmentSession::class, 'session_id');
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
