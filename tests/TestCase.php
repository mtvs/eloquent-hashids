<?php

namespace Mtvs\EloquentHashids\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Database\ConsoleServiceProvider;
use Vinkla\Hashids\HashidsServiceProvider;

abstract class TestCase extends Orchestra 
{
	protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            ConsoleServiceProvider::class,
            HashidsServiceProvider::class,
        ];
    }
}
