<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FocusArea extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps = false;
    public function exercises():BelongsToMany{
        return $this->belongsToMany(Exercise::class,'exercise_focus_area');
    }
}
