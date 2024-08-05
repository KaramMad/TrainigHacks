<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class coachPlan extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function coaches(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_plan', 'exercise_id', 'plan_id');
    }

    public function userProgress(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_plan_progress')->withPivot('status', 'date');
    }

    public function scopeActive($query, $user, $request)
    {
        return $query->where('target', $user->target)->where('level', $user->level)->where('choose', $request->choose);
    }

}
