<?php

namespace FastDog\Adm\Resources\Test;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\FormModelInterface;
use Dg482\Red\Builders\Form\Structure\Fieldset;
use Exception;

/**
 * Class FieldsForm
 * @package FastDog\Adm\Resources\Test
 */
class FieldsForm extends BaseForms implements FormModelInterface
{
    /** @var string */
    public string $title = 'Test Fields';

    /** @var string */
    protected string $formName = 'apm/test';

    /**
     * Identity constructor.
     * @param  Fields  $model
     */
    public function __construct(Fields $model)
    {
        $this->setModel($model);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resourceFields(): array
    {
        return [
            Fieldset::make('Основное', '')->setItems($this->fields()),
        ];
    }
}
