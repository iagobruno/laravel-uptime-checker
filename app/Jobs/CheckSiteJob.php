<?php

namespace App\Jobs;

use App\Enums\CheckStatus;
use App\Models\{Check, Site};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Site $site,
        public Check $check,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->check->update(['status' => CheckStatus::InProgress]);
        $time_start = microtime(true);

        $response = Http::timeout(60)->get($this->site->url);

        $time_end = microtime(true);
        $duration = $time_end - $time_start;

        $this->check->update([
            'status' => $response->successful() ? CheckStatus::Successful : CheckStatus::Failure,
            'duration' => $duration,
            'finished_at' => now(),
            'response' => [
                'http_status' => $response->status(),
            ],
        ]);
    }
}
