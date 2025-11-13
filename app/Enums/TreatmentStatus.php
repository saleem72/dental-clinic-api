<?php

namespace App\Enums;

enum TreatmentStatus: string {
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
