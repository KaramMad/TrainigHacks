<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Coach extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['soso'];
    protected $hidden=['password'];
}
