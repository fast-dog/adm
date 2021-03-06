<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use FastDog\Adm\Database\UserFactory;
use FastDog\Adm\Events\User\InitRoles;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package FastDog\Adm\Models
 */
class User extends Authenticatable implements Model
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * Fields model
     * @return array
     */
    public function getFields(): array
    {
        return [];
    }

    /**
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function updateModel(array $attributes, array $options = []): bool
    {
        if (Hash::check($attributes['password'], $this->password)) {
            return $this->update($attributes, $options);
        }

        return false;
    }

    /**
     * @param  array  $attributes
     * @param  array  $options
     * @return Model
     */
    public function storeModel(array $attributes, array $options = []): Model
    {
        return self::create($attributes);
    }

    /**
     * @return array
     */
    public function getMe(): array
    {
        $roles = collect(['user']);

        event(new InitRoles($roles));

        return [
            'id' => $this->id,
            'username' => $this->name,
            'roleId' => collect('user'),
            'role' => $roles->map(function ($role) {
                return [
                    'id' => $role,
                    'creatorId' => 'system',
                    'status' => 1,
                    'deleted' => 0,
                    'permissions' => $this->getPermissionResource($role),
                ];
            }),
        ];
    }

    /**
     * @param  string  $role
     * @return array
     * @throws BindingResolutionException
     */
    public function getPermissionResource(string $role = 'user'): array
    {
        $permission = [];
        $defaultActions = [
            ['action' => 'create', 'defaultCheck' => false, 'describe' => ''],
            ['action' => 'read', 'defaultCheck' => false, 'describe' => ''],
            ['action' => 'update', 'defaultCheck' => false, 'describe' => ''],
            ['action' => 'delete', 'defaultCheck' => false, 'describe' => ''],
        ];
        /** @var CacheManager $cache */
        $cache = app()->get('cache');
        // 1.3 init resources
        $resources = $cache->getStore()->get('FastDogAdmResources');
        if ($resources) {
            array_map(function (array $resourceData) use ($role, &$permission, $defaultActions) {
                $id = Str::lower($resourceData['idx']);
                /** @var Resource $resource */
                $resource = app()->make($resourceData['idx'].'Resource');
                $permission[] = [
                    'roleId' => $role,
                    'permissionId' => $id,
                    'permissionName' => $resource->getTitle(),
                    'actions' => $defaultActions,
                ];
            }, $resources);
        }

        return $permission;
    }

    /**
     * @return UserFactory
     */
    protected static function newFactory()
    {
        return new UserFactory();
    }
}
