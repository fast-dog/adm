<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Model;
use Dg482\Red\Resource\Resource;
use FastDog\Adm\Database\UserFactory;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
     * Fields model
     * @return array
     */
    public function getFields(): array
    {
        return [];
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function updateModel(array $attributes, array $options = []): bool
    {
        return $this->update($attributes, $options);
    }

    /**
     * @return array
     */
    public function getMe(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->name,
            'roleId' => ['name']
        ];
    }

    /**
     * @return array
     * @throws BindingResolutionException
     */
    public function getPermissionResource(): array
    {
        $permission = [];
        $defaultActions = [
            ['action' => 'create', 'defaultCheck' => true, 'describe' => ''],
            ['action' => 'edit', 'defaultCheck' => true, 'describe' => ''],
            ['action' => 'update', 'defaultCheck' => true, 'describe' => ''],
            ['action' => 'delete', 'defaultCheck' => true, 'describe' => '']
        ];
        /** @var CacheManager $cache */
        $cache = app()->get('cache');
        // 1.3 init resources
        $resources = $cache->getStore()->get('FastDogAdmResources');
        if ($resources) {
            array_map(function (array $resourceData) use (&$permission, $defaultActions) {
                $id = Str::lower($resourceData['idx']);
                /** @var Resource $resource */
                $resource = app()->make($resourceData['idx'] . 'Resource');
                $permission[] = [
                    'roleId' => ['user'],
                    'permissionId' => $id,
                    'permissionName' => $resource->getTitle(),
                    'actions' => $defaultActions
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
