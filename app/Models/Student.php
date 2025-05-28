<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use App\Models\Performance;
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

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
}
