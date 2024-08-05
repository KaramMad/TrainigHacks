<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlanProgress extends Model
{
    use HasFactory;
    protected $table = 'user_plan_progress';
    protected $guarded = [''];
}
