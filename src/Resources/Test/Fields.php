<?php

namespace FastDog\Adm\Resources\Test;

use Illuminate\Database\Eloquent\Model;
use Dg482\Red\Model as RedModel;

/**
 * Class Fields
 * @package FastDog\Adm\Resources\Test
 */
class Fields extends Model implements RedModel
{
    /** @var string  */
    public $table = 'test_field';

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fillable;
    }

    /**
     * @param  array  $request
     * @return RedModel
     */
    public function create(array $request): RedModel
    {
        return $this;
    }
}
