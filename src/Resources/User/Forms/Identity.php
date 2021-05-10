<?php

namespace FastDog\Adm\Resources\User\Forms;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\PasswordField;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Builders\Form\FormModelInterface;
use Dg482\Red\Builders\Form\Structure\Fieldset;
use Exception;
use FastDog\Adm\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class Profile
 * @package App\Models\Forms\User
 */
class Identity extends BaseForms implements FormModelInterface
{
    /**
     * Identity constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->setTitle(trans('adm::resources.user.forms.identity.title'));
        $this->setFormName('user/identity');
        $this->setModel($model);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resourceFields(): array
    {
        return [
            Fieldset::make(trans('adm::resources.user.forms.identity.general'), 'general_fieldset')
                ->setItems($this->fields()),
        ];
    }

    /**
     * Password field overwrite method
     *
     * @param Field $field
     * @return Field
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    public function formFieldPassword(Field $field): Field
    {
        $passwordField = new PasswordField();
        $passwordField->setField($field->getField());
        $passwordField->setName($field->getName());
        $passwordField->hideTable();// hide password field in tables

        return $passwordField;
    }

    /**
     * @param Field $password
     * @param $request
     * @return StringValue
     */
    public function saveFieldPassword(Field $password, $request): StringValue
    {
        return new StringValue(0, Hash::make($request['password']));
    }
}
