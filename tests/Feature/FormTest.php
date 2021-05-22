<?php

namespace FastDog\Adm\Tests\Feature;

use Carbon\Carbon;
use FastDog\Adm\Models\Profile;
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

    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'../../migrations');

        $this->runDatabaseMigrations();

        /** @var User $user */
        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
        ]);

        Profile::create([
            'user_id' => $this->user->id,
            'birthday' => Carbon::now()->format(Carbon::DEFAULT_TO_STRING_FORMAT),
            'website' => 'example.com',
        ]);

        Auth::login($this->user);
    }


    public function testForm()
    {
        $response = $this->get('/api/resource/form?alias=user&id='.$this->user->id);

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
                'values' => [
                    'id' => '1',
                    'name' => 'test',
                    'website' => 'example.com',
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

    public function testFormUpdate()
    {
        $response = $this->json('POST', '/api/resource/form', [
            'form' => 'user/identity',
            'id' => $this->user->id,
            'values' => [
                'id' => $this->user->id,
                'name' => 'new user',
                'email' => 'test1@mail.ru',
                'password' => '12345678',
            ],
        ]);

        $response->assertStatus(200);
    }

    public function testDeleteResourceAssets()
    {
        $response = $this->json('DELETE', '/api/resource/assets', [
            'alias' => 'user',
            'id' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function testFormStructureFields()
    {
        $response = $this->get('/api/resource/fields?alias=user');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
        ]);
    }
}
