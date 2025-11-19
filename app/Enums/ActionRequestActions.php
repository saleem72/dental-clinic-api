<?php

namespace App\Enums;

Enum ActionRequestActions: String {
    Case APPROVE = 'approve';
    Case REJECT = 'reject';
    Case COMPLETE = 'complete';
}
