<?php

namespace FastDog\Adm\Tests\Feature;

use Dg482\Red\Builders\Form\Fields\Field;
use FastDog\Adm\Models\User;
use FastDog\Adm\Resources\Fields\Fields;
use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;

/**
 * Class FormTest
 * @package FastDog\Adm\Tests\Feature
 */
class FormTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '../../migrations');

        $this->runDatabaseMigrations();

        /** @var User $user */
        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
        ]);

        Auth::login($this->user);
    }


    public function testForm()
    {
        $response = $this->get('/api/resource/form?alias=user&id=' . $this->user->id);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'form' => [
                'form' => 'user/identity',
                'items' => [
                    [
                        'type' => 'fieldset',
                        'items' => [
                            'email' => ['type' => 'string'],
                            'password' => ['type' => 'password'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testSwitchAction()
    {
        $response = $this->json('POST', '/api/resource/switch-action', [
            'form' => 'user/profile',
            'action' => 'two-factor-authentication-enabled',
            'checked' => true,
        ]);

        $response->assertStatus(200);
    }

    public function testFormSave()
    {
        $response = $this->json('POST', '/api/resource/form', [
            'form' => 'user/identity',
            'id' => 1,
            'values' => [
                'id' => 1,
                'name' => 'update user',
                'email' => 'test1@mail.ru',
                'password' => 'password',
            ],
        ]);

        $response->assertStatus(200);
    }

    public function testFormCreate()
    {
        $response = $this->json('POST', '/api/resource/form', [
            'form' => 'user/identity',
            'values' => [
                'name' => 'new user',
                'email' => 'test1@mail.ru',
                'password' => '12345678',
            ],
        ]);
        $response->assertStatus(200);
    }


    public function testDeleteResourceItem()
    {
        Fields::create([
            'name' => 'test',
            'email' => 'adm@teest.local',
        ]);

        $response = $this->json('GET', '/api/resource', [
            'alias' => 'fields',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'table' => [
                'data' => [
                    ['name' => 'test', 'id' => 1],
                ],
            ],
        ]);

        $response = $this->json('DELETE', '/api/resource', [
            'alias' => 'fields',
            'id' => 1,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'table' => [
                'data' => [],
            ],
        ]);
    }
}
