<?php

namespace FastDog\Adm\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class FormStructure
 * @package FastDog\Adm\Models
 */
class FormStructure extends Model
{
    use NodeTrait;

    /** @var string */
    protected $table = 'form_structure';

    /**
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['resource'];
    }
}
