<?php

use BinaryCats\Exportify\Events\ExportFailed;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;

it('will_broadcast_on_private_user_channel', function () {
    $user = mock(User::class);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);

    $event = new ExportFailed(
        user: $user,
        exportFactory: 'TestExport',
    );

    $channels = $event->broadcastOn();

    expect($channels)
        ->toBeArray()
        ->toHaveCount(1);

    expect($channels[0])
        ->toBeInstanceOf(PrivateChannel::class);

    expect($channels[0]->name)
        ->toBe('private-user.1');
});

it('will_broadcast_with_correct_data', function () {
    $user = mock(Authenticatable::class);

    $event = new ExportFailed(
        user: $user,
        exportFactory: 'TestExport',
    );

    expect($event->broadcastWith())
        ->toBeArray()
        ->toHaveKey('exportFactory', 'TestExport');
});

it('will_broadcast_with_correct_name', function () {
    $user = mock(Authenticatable::class);

    $event = new ExportFailed(
        user: $user,
        exportFactory: 'TestExport',
    );

    expect($event->broadcastAs())
        ->toBe('TestExport\\Fail');
});
