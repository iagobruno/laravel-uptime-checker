<?php

use Illuminate\Support\Facades\{Artisan, Http, Queue};
use App\Enums\CheckStatus;
use App\Jobs\CheckSiteJob;
use App\Models\{Site};

beforeEach(function () {
    Http::fake([
        'github.com/*' => Http::response('OK', 200),
    ]);
});

test('Sites sem nenhuma checagem devem ser adicionados a fila', function () {
    $site = Site::factory()->forUser()->create();

    Queue::fake();
    Artisan::call('schedule:run');

    Queue::assertPushed(function (CheckSiteJob $job) use ($site) {
        return $job->site->id === $site->id;
    });
});

test('Sites que foram checados recentemente não devem ser adicionados a fila', function () {
    $site = Site::factory()
        ->forUser()
        ->hasChecks(1)
        ->create();

    Queue::fake();
    Artisan::call('schedule:run');

    Queue::assertNotPushed(CheckSiteJob::class);
    // expect($site->checks->count())->toEqual(1);
    // expect($site->lastCheck->created_at->diffInMinutes())->toBeLessThanOrEqual(1);
});

test('Sites que foram checados a muito tempo devem ser adicionados a fila', function () {
    $site = Site::factory()
        ->forUser()
        ->hasChecks(1, ['created_at' => now()->subMinutes(12)])
        ->create();

    Queue::fake();
    Artisan::call('schedule:run');

    Queue::assertPushed(function (CheckSiteJob $job) use ($site) {
        return $job->site->id === $site->id;
    });
    // expect($site->checks->count())->toEqual(2);
    // expect($site->lastCheck->created_at->diffInMinutes())->toBeLessThanOrEqual(1);
});


test('O CheckSiteJob deve fazer a checagem corretamente', function () {
    $site = Site::factory()->forUser()->create();

    Artisan::call('schedule:run');

    $site->load(['checks', 'lastCheck']);
    expect($site->checks->count())->toEqual(1);
    expect($site->lastCheck->status)->toBe(CheckStatus::Completed);
    expect($site->lastCheck->finished_at)->toBeInstanceOf(\Carbon\Carbon::class);
});
