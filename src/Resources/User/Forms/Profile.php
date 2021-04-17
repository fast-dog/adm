<?php

namespace FastDog\Adm\Resources\User\Forms;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Structure\Tabs;
use Exception;
use FastDog\Adm\Models\User;

/**
 * Class Profile
 * @package FastDog\Adm\Resources\User\Forms
 */
class Profile extends BaseForms
{
    /**
     * Identity constructor.
     * @param  User  $model
     */
    public function __construct(User $model)
    {
        $this->setTitle(trans('adm::resources.user.forms.profile.title'));
        $this->setFormName('user/profile');
        $this->setModel($model);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function resourceFields(): array
    {
        $tabs = (new Tabs);

        return [
            $tabs->pushTab(trans('adm::resources.user.forms.profile.personal.title')),
            $tabs->pushTab(trans('adm::resources.user.forms.profile.security.title')),
        ];
    }
}
