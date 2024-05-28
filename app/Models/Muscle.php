<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public $timestamps = false;
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class,'muscle_exercise')->withPivot('exercise_count','total_time','total_calories');
    }
    public function muscleLevels(){
        return $this->hasMany(MuscleLevel::class);
    }

}
