<?php

namespace Mtvs\EloquentHashids\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Vinkla\Hashids\HashidsServiceProvider;

class TestCase extends Orchestra 
{
	protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');

        $this->app['config']->set('hashids', require 'config/hashids.php');
    }

    protected function getPackageProviders($app)
    {
        return [
            HashidsServiceProvider::class,
        ];
    }
}
