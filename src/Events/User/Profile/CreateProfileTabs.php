<?php

namespace FastDog\Adm\Events\User\Profile;

use Dg482\Red\Builders\Form\Structure\Tabs;
use FastDog\Adm\Resources\User\Forms\Profile;

/**
 * Class CreateProfileTabs
 * @package FastDog\Adm\Events\User\Profil
 */
class CreateProfileTabs
{
    /** @var Tabs */
    protected Tabs $tabs;

    /** @var Profile */
    protected Profile $form;

    /**
     * CreateProfileTabs constructor.
     * @param  Tabs  $tabs
     * @param  Profile  $form
     */
    public function __construct(Tabs &$tabs, Profile &$form)
    {
        $this->setTabs($tabs);
        $this->setForm($form);
    }

    /**
     * @return Tabs
     */
    public function getTabs(): Tabs
    {
        return $this->tabs;
    }

    /**
     * @param  Tabs  $tabs
     */
    public function setTabs(Tabs &$tabs): void
    {
        $this->tabs = &$tabs;
    }

    /**
     * @return Profile
     */
    public function getForm(): Profile
    {
        return $this->form;
    }

    /**
     * @param  Profile  $form
     */
    public function setForm(Profile &$form): void
    {
        $this->form = &$form;
    }
}
