<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Support\Facades\Schema;

/**
 * Class ExampleTest
 * @package FastDog\Adm\Tests\Feature
 */
class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'../../migrations');
    }




    public function testTrue()
    {

        $this->assertTrue(Schema::hasTable('test_field'));
    }
}
