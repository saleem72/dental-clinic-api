<?php

namespace App\Helpers;

use App\Models\V1\User;

class UsernameGenerator
{
    public static function generatePatientUsername(): string
    {
        $last = User::where('username', 'like', 'pt-%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return 'pt-000001';
        }

        $num = (int)substr($last->username, 3);
        return 'pt-' . str_pad($num + 1, 6, '0', STR_PAD_LEFT);
    }
}
