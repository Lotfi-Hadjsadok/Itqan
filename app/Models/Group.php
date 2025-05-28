<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Seance;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
