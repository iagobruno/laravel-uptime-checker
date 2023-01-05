<?php

namespace App\Console\Commands;

use App\Enums\CheckStatus;
use App\Jobs\CheckSiteJob;
use App\Models\Site;
use Illuminate\Console\Command;

class CronRunSiteChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run-site-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch to queue all the sites that requires checks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sites = Site::requiresCheck()->get();

        $this->info("Running checks for {$sites->count()} site(s).");

        foreach ($sites as $site) {
            $check = $site->checks()->create([
                'status' => CheckStatus::Queued,
            ]);
            CheckSiteJob::dispatch($site, $check);
        }

        return Command::SUCCESS;
    }
}
