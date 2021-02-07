<?php

namespace FastDog\Adm\Resources\User\Forms;

use App\Models\User;
use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\FormModelInterface;
use Dg482\Red\Builders\Form\Structure\Fieldset;
use Exception;

/**
 * Class Profile
 * @package App\Models\Forms\User
 */
class Identity extends BaseForms implements FormModelInterface
{
    /** @var string */
    public string $title = 'Данные пользователя';

    /** @var string */
    protected string $formName = 'user/identity';

    /**
     * Identity constructor.
     * @param  User  $model
     */
    public function __construct(User $model)
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
