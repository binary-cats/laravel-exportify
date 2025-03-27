<?php

namespace BinaryCats\Exportify\Tests\Feature\Exceptions;

use BinaryCats\Exportify\Exceptions\ExportifyException;

it('will_create_exception_for_missing_factory', function(): void
{
    // Create an exception for a missing factory
    $exception = ExportifyException::missingFactory('test-factory');
    
    // Assert that the exception message is formatted correctly
    expect($exception)
        ->toBeInstanceOf(ExportifyException::class)
        ->getMessage()
        ->toBe('Export factory [test-factory] is not registered.');
});