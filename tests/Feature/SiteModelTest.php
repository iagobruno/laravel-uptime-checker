<?php

use App\Models\{Site, User};

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
