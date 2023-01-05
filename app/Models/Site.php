<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\Builder;

class Site extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function lastCheck(): HasOne
    {
        return $this->hasOne(Check::class)->latestOfMany();
    }

    public function scopeRequiresCheck(Builder $query)
    {
        return $query
            ->doesntHave('checks')
            ->orWhereRelation('lastCheck', 'created_at', '<=', now()->subMinutes(10));
    }
}
