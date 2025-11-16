<?php

namespace App\Enums;

enum ActionRequestType: string
{
    case POTENTIAL_PATIENT = 'potential_patient';
    case RESCHEDULE_SESSION = 'reschedule_session';

    // Future:
    // case CANCEL_TREATMENT = 'cancel_treatment';
    // case ADD_PROCEDURE = 'add_procedure';
    // case FOLLOW_UP = 'follow_up';
    // case LAB_WORK = 'lab_work';

    public function label(): string
    {
        return match ($this) {
            self::POTENTIAL_PATIENT => 'Potential Patient',
            self::RESCHEDULE_SESSION => 'Reschedule Session',
        };
    }
}
