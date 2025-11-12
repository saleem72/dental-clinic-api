<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
