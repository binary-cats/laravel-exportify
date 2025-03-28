<?php

namespace BinaryCats\Exportify\Tests\Feature\Concerns;

use BinaryCats\Exportify\Concerns\InteractsWithArguments;

it('handle interaction with arguments via a trait', function (): void {
    $withArguments = $this->mock(InteractsWithArguments::class);

    $this->assertEquals([], $withArguments->arguments());
    $this->assertEquals('default=value', $withArguments->arguments('does-not-exist', 'default=value'));
    $this->assertnull($withArguments->arguments('does-not-exist'));

    $arguments = [
        'foo' => 'bar',
    ];

    // set arguments
    $this->assertSame($withArguments, $withArguments->arguments($arguments));

    $this->assertEquals($arguments, $withArguments->arguments());
    $this->assertEquals('bar', $withArguments->arguments('foo'));
    $this->assertEquals('123', $withArguments->arguments('does-not-exist', '123'));
    $this->assertnull($withArguments->arguments('does-not-exist'));
});

