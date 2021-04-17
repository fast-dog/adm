<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Models\User;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'../../migrations');

        $this->runDatabaseMigrations();
    }


    public function testForm()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($user);

        $response = $this->get('/api/resource/form?alias=user&id='.$user->id);

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
}
