<?php

namespace Tests\Feature\Jobs;

use BinaryCats\Exportify\Events\ExportSuccessful;
use BinaryCats\Exportify\Jobs\DispatchExportCompletedNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Event;
use Mockery;

test('it_will_dispatch_event_when_user_is_present', function (): void {
    // Arrange
    Event::fake();
    $user = Mockery::mock(Authenticatable::class);
    $filePath = 'exports/test.csv';
    $disk = 'local';

    $job = new DispatchExportCompletedNotification(
        filePath: $filePath,
        disk: $disk,
        user: $user
    );

    // Act
    $job->handle();

    // Assert
    Event::assertDispatched(ExportSuccessful::class, function ($event) use ($user, $filePath, $disk) {
        return $event->user === $user
            && $event->filePath === $filePath
            && $event->disk === $disk;
    });
});

test('it_will_not_dispatch_event_when_user_is_null', function (): void {
    // Arrange
    Event::fake();
    $filePath = 'exports/test.csv';
    $disk = 'local';

    $job = new DispatchExportCompletedNotification(
        filePath: $filePath,
        disk: $disk,
        user: null
    );

    // Act
    $job->handle();

    // Assert
    Event::assertNotDispatched(ExportSuccessful::class);
});
