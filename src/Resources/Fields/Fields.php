<?php

namespace FastDog\Adm\Resources\Fields;

use Illuminate\Database\Eloquent\Model;
use Dg482\Red\Model as RedModel;

/**
 * Class Fields
 * @package FastDog\Adm\Resources\Test
 */
class Fields extends Model implements RedModel
{
    /** @var string */
    public $table = 'test_field';

    /** @var string[] */
    protected $fillable = ['name'];

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fillable;
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
}
