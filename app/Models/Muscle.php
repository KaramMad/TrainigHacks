<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;
    protected $guarded=['sso'];
    public function exercises(){
        return $this->belongsToMany(Exercise::class);
    }
}
