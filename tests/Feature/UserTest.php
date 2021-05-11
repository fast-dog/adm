<?php

namespace FastDog\Adm\Tests\Feature;

use Dg482\Red\Builders\Form\Fields\TableField;
use FastDog\Adm\Models\User;
use FastDog\Adm\Resources\User\UserResource;
use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\FortifyServiceProvider;

/**
 * Class UserTests
 * @package FastDog\Adm\Tests\Feature
 */
class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @var UserResource */
    protected UserResource $resource;

    /** @var User */
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        app()->register(FortifyServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__.'../../migrations');

        $this->runDatabaseMigrations();

        /** @var UserResource $resource */
        $this->resource = $this->app->get(UserResource::class);

        /** @var User $user */
        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($this->user);
    }

    public function testUser()
    {
        $permissionDefaultResource = $this->user->getPermissionResource();

        $this->assertCount(1, $permissionDefaultResource);
    }

    public function testUserInfo()
    {

        $response = $this->get('/api/user/info');

        $response->assertStatus(200);
    }

    public function testUserNav()
    {
        $response = $this->get('/api/user/nav');

        $response->assertStatus(200);

        $response->assertJson([
            'result' => [['component' => 'RouteView', 'name' => 'dashboard']],
        ]);
    }

    public function testUserRole()
    {
        $response = $this->get('/api/role');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'roleId' => 'user',
        ]);
    }

    public function testProfileForm()
    {
        $response = $this->post('/api/user/two-factor-authentication');

        $response->assertStatus(302);

        $this->assertTrue(session('status') == 'two-factor-authentication-enabled');

        $response = $this->get('/api/user/profile');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'form' => [
                'form' => 'user/profile',
                'items' => [
                    [
                        'type' => 'tabs',
                        'items' => [
                            ['field' => 'tab-personal'],
                            ['field' => 'tab-security'],
                        ],
                    ],
                ],
            ],
        ]);

    }

    public function testHasOneResourceField()
    {
        $hasOneForm = $this->resource->hasOne('profile')->getForm();

        $this->assertEquals('user/profile', $hasOneForm['form']);
    }

    public function testHasManyResourceField()
    {
        $fieldTable = (new TableField)->setField('fake_table_profile');

        $this->resource->hasMany('profile', $fieldTable);

        $this->assertEquals('profile@fake_table_profile', $fieldTable->getField());
    }
}
