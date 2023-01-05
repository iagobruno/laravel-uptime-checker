<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Check extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }
}
