<?php

namespace FastDog\Adm\Tests\Feature;

use FastDog\Adm\Models\User;
use FastDog\Adm\Resources\User\UserResource;
use FastDog\Adm\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserTests
 * @package FastDog\Adm\Tests\Feature
 */
class UserTest extends TestCase
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

    public function testUser()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        $permissionDefaultResource = $user->getPermissionResource();

        $this->assertCount(2, $permissionDefaultResource);
    }

    public function testUserInfo()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($user);

        $response = $this->get('/api/user/info');

        $response->assertStatus(200);
    }

    public function testUserNav()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($user);

        $response = $this->get('/api/user/nav');

        $response->assertStatus(200);

        $response->assertJson([
            'result' => [['component' => 'RouteView', 'name' => 'dashboard']],
        ]);
    }

    public function testUserRole()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'adm@test.local',
            'password' => 'password',
        ]);

        Auth::login($user);

        $response = $this->get('/api/role');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'roleId' => 'user',
        ]);
    }
}
