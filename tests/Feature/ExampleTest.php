<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Resources\Test\Fields;

use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();
    }

    public function testTrue()
    {
        $this->assertTrue(1 === 1);
    }
}
