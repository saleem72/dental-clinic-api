<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'bio',
        'commission_rate',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            // 'commission_rate' => do i need to cast this decimal column
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function patients() {
        return $this->hasMany(Patient::class);
    }
}
