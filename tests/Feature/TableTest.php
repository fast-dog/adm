<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Adapters\EloquentAdapter;
use FastDog\Adm\Resources\Test\Fields;
use FastDog\Adm\Resources\Test\FieldsForm;
use FastDog\Adm\Resources\Test\FieldsResource;
use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class ExampleTest
 * @package FastDog\Adm\Tests\Feature
 */
class TableTest extends TestCase
{
    use DatabaseMigrations;

    /** @var FieldsResource */
    protected FieldsResource $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'../../migrations');

        /** @var FieldsResource $resource */
        $this->resource = $this->app->get(FieldsResource::class);
    }


    public function testInit()
    {
        $this->assertTrue(Schema::hasTable('test_field'));


        /** @var FieldsForm $form */
        $form = $this->app->get(FieldsForm::class);

        $this->resource->setForm($form);

        $this->assertInstanceOf(EloquentAdapter::class, $this->resource->getAdapter());

        $fields = $this->resource->fields();

        $this->assertCount(36, $fields);
    }

    public function testAdapter()
    {
        Fields::create([
            'name' => 'test',
        ]);

        Fields::create([
            'name' => 'test 2',
        ]);

        $adapter = $this->resource->getAdapter();
        $adapter->setModel((new Fields));
        $adapter->setFilter(null);

        $this->assertInstanceOf(Fields::class, $adapter->getModel());

        app()->get('request')->merge([
            'id' => 1,
        ]);

        $record = $adapter->read(1);

        $this->assertEquals(1, $record['id']);
        $this->assertEquals('test', $record['name']);

        app()->get('request')->merge([
            'id' => -1,
        ]);

        $records = $adapter->read(25);

        $this->assertEquals(1, $records['current_page']);
        $this->assertCount(2, $records['data']);
    }
}
