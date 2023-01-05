<?php

namespace App\Jobs;

use App\Enums\CheckStatus;
use App\Models\Site;
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
        $time_start = microtime(true);

        $response = Http::timeout(60)->get('https://google.com');

        $time_end = microtime(true);
        $duration = $time_end - $time_start;

        $this->site->checks()->create([
            'status' => $response->successful() ? CheckStatus::Completed : CheckStatus::Failed,
            'duration' => $duration,
            'finished_at' => $response->successful() ? now() : null,
            'response' => [
                'http_status' => $response->status(),
            ],
        ]);
    }
}
