<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $guarded = [''];
    public function billable():MorphTo
    {
        return $this->morphTo();
    }

}
