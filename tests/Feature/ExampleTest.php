<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Adapters\EloquentAdapter;
use FastDog\Adm\Resources\Test\Fields;
use FastDog\Adm\Resources\Test\FieldsForm;
use FastDog\Adm\Resources\Test\FieldsResource;
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

    public function testInit()
    {
        $this->assertTrue(Schema::hasTable('test_field'));

        /** @var FieldsResource $resource */
        $resource = $this->app->get(FieldsResource::class);

        /** @var FieldsForm $form */
        $form = $this->app->get(FieldsForm::class);

        $resource->setForm($form);

        $this->assertInstanceOf(EloquentAdapter::class, $resource->getAdapter());
    }
}
