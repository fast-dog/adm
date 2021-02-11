<?php

namespace FastDog\Adm\Tests;

use FastDog\Adm\AdmServiceProvider;

/**
 * Class TestCase
 * @package FastDog\Adm\Tests
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [AdmServiceProvider::class];
    }
}
