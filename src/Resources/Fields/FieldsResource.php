<?php

namespace FastDog\Adm\Resources\Fields;

use Dg482\Red\Builders\Form;
use Dg482\Red\Resource\Resource;

/**
 * Class FieldsResource
 * @package FastDog\Adm\Resources\Test
 */
class FieldsResource extends Resource
{

    /** @var string */
    protected string $title = 'Тестовые поля UI';

    /**
     * @var string[]
     */
    protected array $relations = [

    ];

    /** @var array */
    protected array $validators = [
    ];

    /**
     * Скрытые поля
     * @var string[]
     */
    protected array $hidden_fields = [
    ];

    /**
     * Подписи
     * @var string[]
     */
    protected array $labels = [
    ];

    /**
     * @param  string  $context
     * @return Resource
     */
    public function initResource(string $context = ''): Resource
    {
        $this->setContext(Form::class);

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
