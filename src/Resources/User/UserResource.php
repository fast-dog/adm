<?php

namespace FastDog\Adm\Resources\User;

use Dg482\Red\Resource\Resource;

/**
 * Class UserResource
 * @package App\Resources
 */
class UserResource extends Resource
{
    /** @var string */
    protected string $title = 'Пользователи';

    /**
     * Отношения к профилю и файлам пользователя
     * @var string[]
     */
    protected array $relations = [
//        'profile' => UserProfileResource::class,
//        'files' => UserFilesResource::class,
    ];

    /** @var array */
    protected array $validators = [
        'email' => ['required', 'email', 'max:50'],
    ];

    /**
     * Скрытые поля
     * @var string[]
     */
    protected array $hidden_fields = [
        'email_verified_at',
        'verify_token',
        'remember_token',
        'online_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Подписи
     * @var string[]
     */
    protected array $labels = [
        'email' => 'Email',
        'name' => 'Имя',
        'status' => 'Статус',
        'role' => 'Привилегии',
    ];

    /**
     * @param  string  $context
     * @return Resource
     */
    public function initResource(string $context = ''): Resource
    {
        $this->setContext(__CLASS__);

        return $this;
    }

    /**
     * @param $paginator
     * @return array
     */
    protected function getPagination($paginator): array
    {
        return [
            'total' => $paginator->total(),
            'current' => $paginator->currentPage(),
            'last' => $paginator->lastPage(),
            'pageSize' => $paginator->perPage(),
        ];
    }
}
