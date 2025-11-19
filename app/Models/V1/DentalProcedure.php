<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class DentalProcedure extends Model
{
    protected $fillable = ['name', 'dental_code', 'fee', 'is_active'];

    public function treatmentProcedures() {
        return $this->hasMany(TreatmentProcedure::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }
}
