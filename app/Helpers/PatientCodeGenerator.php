<?php

namespace App\Helpers;

use App\Models\V1\Patient;

class PatientCodeGenerator
{
    public static function generate(): string
    {
        $year = now()->year;

        $last = Patient::where('patient_code', 'like', "PAT-$year-%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return "PAT-$year-000001";
        }

        $num = (int)substr($last->patient_code, 9);

        return "PAT-$year-" . str_pad($num + 1, 6, '0', STR_PAD_LEFT);
    }
}
