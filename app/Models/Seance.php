<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
}
