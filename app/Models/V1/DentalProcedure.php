<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class DentalProcedure extends Model
{
    protected $fillable = ['name', 'dental_code', 'fee'];

    public function treatmentProcedures() {
        return $this->hasMany(TreatmentProcedure::class);
    }
}
