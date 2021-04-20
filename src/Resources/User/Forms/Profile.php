<?php

namespace FastDog\Adm\Resources\User\Forms;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Builders\Form\Structure\Tabs;
use Exception;
use FastDog\Adm\Events\User\Profile\CreateProfileTabPersonal;
use FastDog\Adm\Events\User\Profile\CreateProfileTabs;
use FastDog\Adm\Events\User\Profile\CreateProfileTabSecurity;
use FastDog\Adm\Events\User\Profile\CreateResourceFields;
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
        $tabs = (new Tabs)->setField('profile_tabs');

        event(new CreateProfileTabs($tabs, $this));

        $tabPersonal = $tabs->pushTab(trans('adm::resources.user.forms.profile.personal.title'));
        $tabPersonal->setField('tab-personal');

        event(new CreateProfileTabPersonal($tabPersonal, $this));

        $tabSecurity = $tabs->pushTab(trans('adm::resources.user.forms.profile.security.title'));
        $tabSecurity->setField('tab-security');

        event(new CreateProfileTabSecurity($tabSecurity, $this));

        $result = [$tabs];

        event(new CreateResourceFields($result, $this));

        return $result;
    }
}
