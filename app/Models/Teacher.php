<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($teacher) {
            $teacher->user()->delete();
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
