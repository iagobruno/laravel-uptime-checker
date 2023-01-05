<?php

namespace App\Actions;

use App\Jobs\CheckSiteJob;
use App\Models\Site;

class DispatchSitesToQueue
{
    public function __invoke()
    {
        $sitesThatShouldBeChecked = Site::requiresCheck()->get();

        foreach ($sitesThatShouldBeChecked as $site) {
            CheckSiteJob::dispatch($site);
        }
    }
}
