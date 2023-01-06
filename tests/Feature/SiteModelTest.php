<?php

use App\Models\{Check, Site, User};

test('O escopo "requiresCheck" deve funcionar corretamente', function () {
    $user = User::factory()->create();

    Site::factory()->for($user)->create();
    Site::factory()->for($user)->hasChecks()->create();
    Site::factory()
        ->for($user)
        ->hasChecks(1, ['created_at' => now()->subMinutes(11)])
        ->create();

    $sitesThatShouldBeChecked = Site::requiresCheck()->get();

    expect($sitesThatShouldBeChecked->count())->toEqual(2);
});

test('O método "generateUptimeReport" deve funcionar corretamente', function () {
    $site = Site::factory()->forUser()->create();
    Check::factory(75)->for($site)->successful()->finishedAt(now())->create();
    Check::factory(25)->for($site)->failure()->finishedAt(now())->create();
    Check::factory(40)->for($site)->successful()->finishedAt(now()->subDays(4))->create();
    Check::factory(70)->for($site)->failure()->finishedAt(now()->subDays(15))->create();
    Check::factory(196)->for($site)->successful()->finishedAt(now()->subDays(15))->create();
    Check::factory(400)->for($site)->failure()->finishedAt(now()->subDays(60))->create();
    Check::factory(123)->for($site)->successful()->finishedAt(now()->subDays(60))->create();

    $uptimeReport = $site->generateUptimeReport();

    expect($uptimeReport['last24Hours'])->toEqual(75);
    expect($uptimeReport['last7Days'])->toEqualWithDelta(82.14, 0.01);
    expect($uptimeReport['last30Days'])->toEqualWithDelta(76.60, 0.01);
    expect($uptimeReport['last90Days'])->toEqualWithDelta(46.71, 0.01);
});
