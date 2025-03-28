<?php

namespace Tests\Feature\Jobs;

use BinaryCats\Exportify\Events\ExportFailed;
use BinaryCats\Exportify\Jobs\DispatchExportFailedNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Event;
use Mockery;

test('it_will_dispatch_event_when_user_is_present', function (): void {
    // Arrange
    Event::fake();
    $user = Mockery::mock(Authenticatable::class);
    $filePath = 'exports/test.csv';
    $disk = 'local';
    $exportFactory = 'TestExport';
    $guard = Mockery::mock(Guard::class);

    $job = new DispatchExportFailedNotification(
        filePath: $filePath,
        disk: $disk,
        user: $user,
        exportFactory: $exportFactory
    );

    // Act
    $job->handle($guard);

    // Assert
    Event::assertDispatched(ExportFailed::class, function ($event) use ($user, $filePath, $disk, $exportFactory) {
        return $event->user === $user
            && $event->filePath === $filePath
            && $event->disk === $disk
            && $event->exportFactory === $exportFactory;
    });
});

test('it_will_not_dispatch_event_when_user_is_null', function (): void {
    // Arrange
    Event::fake();
    $filePath = 'exports/test.csv';
    $disk = 'local';
    $exportFactory = 'TestExport';
    $guard = Mockery::mock(Guard::class);

    $job = new DispatchExportFailedNotification(
        filePath: $filePath,
        disk: $disk,
        user: null,
        exportFactory: $exportFactory
    );

    // Act
    $job->handle($guard);

    // Assert
    Event::assertNotDispatched(ExportFailed::class);
});
