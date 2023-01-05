<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Url extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNeedToBeChecked(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query
            ->whereNull('last_checked_at')
            ->orWhere('last_checked_at', '<=', now()->subMinutes(10));
    }
}
