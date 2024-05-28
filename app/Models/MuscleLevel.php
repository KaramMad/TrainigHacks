<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuscleLevel extends Model
{
    use HasFactory;

    protected $guarded=[''];
    public function muscle(){
        return $this->belongsTo(Muscle::class);
    }
}
