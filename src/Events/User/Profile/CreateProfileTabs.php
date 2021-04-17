<?php

namespace FastDog\Adm\Events\User\Profile;

use Dg482\Red\Builders\Form\Structure\Tabs;

/**
 * Class CreateProfileTabs
 * @package FastDog\Adm\Events\User\Profil
 */
class CreateProfileTabs
{
    /** @var Tabs */
    protected Tabs $tabs;

    /**
     * CreateProfileTabs constructor.
     * @param Tabs $tabs
     */
    public function __construct(Tabs &$tabs)
    {
        $this->setTabs($tabs);
    }

    /**
     * @return Tabs
     */
    public function getTabs(): Tabs
    {
        return $this->tabs;
    }

    /**
     * @param Tabs $tabs
     */
    public function setTabs(Tabs &$tabs): void
    {
        $this->tabs = &$tabs;
    }
}
