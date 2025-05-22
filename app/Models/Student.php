<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
