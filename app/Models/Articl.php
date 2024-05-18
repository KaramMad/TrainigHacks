<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articl extends Model
{
    use HasFactory;
    protected $fillable=[
        'Author_Name',
        'title',
        'Image',
        'Article',
    ];
}
