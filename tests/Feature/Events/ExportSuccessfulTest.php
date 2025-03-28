<?php

use BinaryCats\Exportify\Events\ExportSuccessful;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;

it('will_broadcast_on_private_user_channel', function () {
    $user = mock(User::class);
    $user->shouldReceive('getAuthIdentifier')->andReturn(1);

    $event = new ExportSuccessful(
        user: $user,
        filePath: 'exports/test.csv',
        disk: 'local'
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

    $event = new ExportSuccessful(
        user: $user,
        filePath: 'exports/test.csv',
        disk: 'local'
    );

    expect($event->broadcastWith())
        ->toBeArray()
        ->toHaveKey('filePath', 'exports/test.csv')
        ->toHaveKey('disk', 'local');
});

it('will_broadcast_with_null_disk', function () {
    $user = mock(Authenticatable::class);

    $event = new ExportSuccessful(
        user: $user,
        filePath: 'exports/test.csv'
    );

    expect($event->broadcastWith())
        ->toBeArray()
        ->toHaveKey('filePath', 'exports/test.csv')
        ->toHaveKey('disk', null);
});
