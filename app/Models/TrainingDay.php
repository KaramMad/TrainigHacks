<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'day'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_training_days');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'training_day_exercises');
    }
    

}
