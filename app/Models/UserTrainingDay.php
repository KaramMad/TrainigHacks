<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTrainingDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'training_days_id'

    ];
    protected $table = 'user_training_days';

    public function trainingDay()
    {
        return $this->belongsTo(TrainingDay::class, 'training_days_id');
    }

}
