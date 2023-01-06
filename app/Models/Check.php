<?php

namespace App\Models;

use App\Enums\CheckStatus;
use App\Events\CheckStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Check extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => CheckStatus::class,
        'response' => 'array',
        'finished_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }


    public static function booted()
    {
        static::updated(function (Check $check) {
            if ($check->wasChanged('status')) {
                CheckStatusChanged::dispatch(
                    $check,
                    $check->status,
                    $check->getOriginal('status'),
                );
            }
        });
    }
}
