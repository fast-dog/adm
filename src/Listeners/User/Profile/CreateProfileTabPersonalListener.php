<?php

namespace FastDog\Adm\Listeners\User\Profile;

use Dg482\Red\Builders\Form\Fields\DateField;
use Dg482\Red\Builders\Form\Fields\StringField;
use FastDog\Adm\Events\User\Profile\CreateProfileTabPersonal;

/**
 * Class CreateProfileTabPersonalListener
 * @package FastDog\Adm\Listeners\User\Profile
 */
class CreateProfileTabPersonalListener
{
    /**
     * @param CreateProfileTabPersonal $event
     */
    public function handle(CreateProfileTabPersonal $event)
    {
        $formModel = $event->getForm();
        $tabPane = $event->getTabPane();
        // add form element to tabPane


        $user = auth()->user();
        $profile = $user->profile;

        $tabPane->pushItem((new StringField)
            ->setField('username')
            ->setName('ФИО')
            ->setRequired()
            ->setValidators(['max:50'])
            ->setValue($user->name));

        $tabPane->pushItem((new DateField)
            ->setField('birthday')
            ->setName('Дата рождения')
            ->setValue($user->profile->birthday ?? ''));

        $tabPane->pushItem((new StringField)
            ->setField('website')
            ->setName('WEB сайт')
            ->setValue($user->profile->website ?? ''));


        $event->setTabPane($tabPane);
        $event->setForm($formModel);
    }
}
