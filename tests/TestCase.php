<?php

namespace Ansezz\Gamify\Tests;

use Ansezz\Gamify\Facades\GamifyFacade;
use Ansezz\Gamify\GamifyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            GamifyServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Gamify' => GamifyFacade::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}

