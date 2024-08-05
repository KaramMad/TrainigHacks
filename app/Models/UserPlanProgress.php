<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserPlanProgress extends Model
{
    use HasFactory;

    protected $table = 'user_plan_progress';
    protected $guarded = [''];

    public function getCreatedAtAttribute($value): string
    {
        return $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function scopeProgress($query, $user, $plan)
    {
        return $query->where('user_id', $user->id)->where('plan_id', $plan->id)->where('created_at', now()->format('Y-m-d'));
    }public function scopeLastDay($query, $user, $plan)
    {
        return $query->where('user_id', $user->id)->where('plan_id', $plan->id);
    }

    public function scopeNextDay($query, $user, $plan, $nexDay)
    {
        return $query->where('user_id', $user->id)->where('plan_id', $plan->id)->where('created_at', $nexDay);
    }
}
