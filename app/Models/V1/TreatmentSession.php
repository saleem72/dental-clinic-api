<?php

namespace App\Models\V1;

use App\Enums\TreatmentSessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'dentist_id',
        'start_at',
        'estimated_time',
        'notes',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'status' => TreatmentSessionStatus::class,
    ];

    public function treatmentCourse()
    {
        return $this->belongsTo(TreatmentCourse::class);
    }

    public function procedures()
    {
        return $this->hasMany(TreatmentProcedure::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id');
    }

    // public function patient()
    // {
    //     return $this->treatment->patient();
    // }
}
