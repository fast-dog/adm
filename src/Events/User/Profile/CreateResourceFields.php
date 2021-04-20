<?php

namespace FastDog\Adm\Events\User\Profile;

use FastDog\Adm\Resources\User\Forms\Profile;

/**
 * Class CreateResourceFields
 * @package FastDog\Adm\Events\User\Profile
 */
class CreateResourceFields
{
    /** @var array */
    protected array $tabsResult;

    /** @var Profile */
    protected Profile $form;

    /**
     * CreateResourceFields constructor.
     * @param  array  $tabsResult
     * @param  Profile  $form
     */
    public function __construct(array &$tabsResult, Profile $form)
    {
        $this->tabsResult = &$tabsResult;
        $this->setForm($form);
    }

    /**
     * @return array
     */
    public function getTabsResult(): array
    {
        return $this->tabsResult;
    }

    /**
     * @param  array  $tabsResult
     */
    public function setTabsResult(array $tabsResult): void
    {
        $this->tabsResult = $tabsResult;
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
