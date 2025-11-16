<?php

namespace App\Models\V1;

use App\Enums\ActionRequestStatus;
use App\Enums\ActionRequestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_id',
        'assigned_to_id',
        'patient_id',
        'treatment_course_id',
        'treatment_session_id',
        'type',
        'status',
        'payload',
        'doctor_note',
        'resolved_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'resolved_at' => 'datetime',

        // Enum casting
        'type' => ActionRequestType::class,
        'status' => ActionRequestStatus::class,
    ];

    // -----------------------
    //   RELATIONSHIPS
    // -----------------------

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentCourse()
    {
        return $this->belongsTo(TreatmentCourse::class);
    }

    public function treatmentSession()
    {
        return $this->belongsTo(TreatmentSession::class);
    }

    // -----------------------
    //   SCOPES
    // -----------------------

    public function scopePending($query)
    {
        return $query->where('status', ActionRequestStatus::PENDING);
    }
}
