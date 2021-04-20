<?php

namespace FastDog\Adm\Events\User\Profile;

use Dg482\Red\Builders\Form\Structure\TabPane;
use FastDog\Adm\Resources\User\Forms\Profile;

/**
 * Class CreateProfileTabPersonal
 * @package FastDog\Adm\Events\User\Profile
 */
class CreateProfileTabPersonal
{
    /** @var TabPane */
    protected TabPane $tabPane;

    /** @var Profile */
    protected Profile $form;

    /**
     * CreateProfileTabPersonal constructor.
     * @param  TabPane  $tabPane
     * @param  Profile  $form
     */
    public function __construct(TabPane &$tabPane, Profile $form)
    {
        $this->setTabPane($tabPane);
        $this->setForm($form);
    }

    /**
     * @return TabPane
     */
    public function getTabPane(): TabPane
    {
        return $this->tabPane;
    }

    /**
     * @param  TabPane  $tabPane
     */
    public function setTabPane(TabPane &$tabPane): void
    {
        $this->tabPane = &$tabPane;
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
