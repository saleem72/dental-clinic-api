<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum ErrorCode {
    public const SERVER_ERROR = 1000;
    public const UNAUTHENTICATED = 1001;
    public const FORBIDDEN = 1002;
    public const NOT_FOUND = 1003;
    public const METHOD_NOT_ALLOWED = 1004;
    public const VALIDATION_FAILED = 1005;
    public const TOO_MANY_REQUESTS = 1006;
    public const RESOURCE_NOT_FOUND = 1007;

    //
    public const INVALID_Credentials = 1008;
    public const USER_Temporally_BLOCKED = 1009;
    public const USER_HAS_To_CHANGE_PASSWORD = 1010;
    public const WRONG_PASSWORD = 1011;
}
