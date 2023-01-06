<?php

namespace App\Models;

use App\Enums\CheckStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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

    public function generateUptimeReport()
    {
        $results = DB::table('checks')
            ->where('site_id', $this->id)
            ->where('finished_at', '>=', now()->subMonths(3))
            ->get();

        $resultsLast24Hours = $results->where('finished_at', '>=', now()->subDay());
        $successesLast24Hours = $resultsLast24Hours->whereStrict('status', CheckStatus::Successful->value);
        $last24Hours = $successesLast24Hours->count() / $resultsLast24Hours->count() * 100;

        $resultsLast7Days = $results->where('finished_at', '>=', now()->subWeek());
        $successesLast7Days = $resultsLast7Days->whereStrict('status', CheckStatus::Successful->value);
        $last7Days = $successesLast7Days->count() / $resultsLast7Days->count() * 100;

        $resultsLast30Days = $results->where('finished_at', '>=', now()->subMonth());
        $successesLast30Days = $resultsLast30Days->whereStrict('status', CheckStatus::Successful->value);
        $last30Days = $successesLast30Days->count() / $resultsLast30Days->count() * 100;

        $resultsLast90Days = $results;
        $successesLast90Days = $resultsLast90Days->whereStrict('status', CheckStatus::Successful->value);
        $last90Days = $successesLast90Days->count() / $resultsLast90Days->count() * 100;

        return compact([
            'last24Hours',
            'last7Days',
            'last30Days',
            'last90Days',
        ]);
    }
}
