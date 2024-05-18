<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;
    protected $guarded=['soso'];

    public function muscles(){
        return $this->belongsToMany(Muscle::class);
    }
}
