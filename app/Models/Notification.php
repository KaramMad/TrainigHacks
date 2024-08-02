<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = [''];
    protected $casts = ['data'=>'array'];
    public function users()
    {
        return $this->belongsToMany(User::class,'notification_users');
    }
}
