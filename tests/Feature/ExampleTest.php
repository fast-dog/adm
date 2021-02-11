<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class ExampleTest
 * @package FastDog\Adm\Tests\Feature
 */
class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();
    }

    public function testTrue()
    {
        $this->assertTrue(true);
    }
}
