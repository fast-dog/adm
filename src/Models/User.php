<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function updateModel(array $attributes, array $options = []): bool
    {
        return $this->update($attributes, $options);
    }

    /**
     * @return array
     */
    public function getPermissionResource(): array
    {
        $permission = [];


        return $permission;
    }
}
