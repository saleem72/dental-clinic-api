<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'medical_notes',
        'medical_history',
        'dentist_id',
        'patient_code'
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'datetime',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function dentist() {
        return $this->belongsTo(Dentist::class);
    }
}
