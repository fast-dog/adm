<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Adapters\EloquentAdapter;
use FastDog\Adm\Models\User;
use FastDog\Adm\Resources\Fields\Fields;
use FastDog\Adm\Resources\Fields\FieldsForm;
use FastDog\Adm\Resources\Fields\FieldsResource;
use FastDog\Adm\Resources\User\Forms\Identity;
use FastDog\Adm\Resources\User\UserResource;
use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Class ExampleTest
 * @package FastDog\Adm\Tests\Feature
 */
class TableTest extends TestCase
{
    use DatabaseMigrations;

    /** @var UserResource */
    protected UserResource $resource;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'../../migrations');

        $this->runDatabaseMigrations();

        /** @var UserResource $resource */
        $this->resource = $this->app->get(UserResource::class);
    }


    public function testInit()
    {
        $this->assertTrue(Schema::hasTable('test_field'));


        /** @var Identity $form */
        $form = $this->app->get(Identity::class);

        $this->resource->setForm($form);

        $this->assertInstanceOf(EloquentAdapter::class, $this->resource->getAdapter());

        $fields = $this->resource->fields();

        $this->assertCount(4, $fields);
    }

    public function testAdapter()
    {
        User::factory()->create([
            'name' => 'test 1',
            'email' => 'adm1@test.local',
            'password' => 'password',
        ]);
        User::factory()->create([
            'name' => 'test 2',
            'email' => 'adm2@test.local',
            'password' => 'password',
        ]);

        $adapter = $this->resource->getAdapter();
        $adapter->setModel((new User));
        $adapter->setFilter(null);

        $this->assertInstanceOf(User::class, $adapter->getModel());

        $records = $adapter->read(25);

        $this->assertEquals(1, $records['current_page']);
        $this->assertCount(2, $records['data']);

        app()->get('request')->merge([
            'id' => 1,
        ]);

        $record = $adapter->read(1);

        $this->assertEquals(1, $record['id']);
        $this->assertEquals('test 1', $record['name']);
    }


    public function testUserResource()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($user);

        User::factory(149)->create();

        $response = $this->get('/api/resource?alias=user');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'table' => [
                'rowActions' => [
                    ['id' => 'update'],
                    ['id' => 'delete'],
                ],
                'pagination' => [
                    'total' => 150,
                ],
            ],
        ]);
    }
}
