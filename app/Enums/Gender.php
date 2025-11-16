<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum Gender: String {
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
