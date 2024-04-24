<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Coach extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['id'];
    protected $hidden = ['password'];
}
