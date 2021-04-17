<?php

namespace FastDog\Adm\Events\User\Profile;

use Dg482\Red\Builders\Form\Structure\TabPane;

/**
 * Class CreateProfileTabPersonal
 * @package FastDog\Adm\Events\User\Profile
 */
class CreateProfileTabPersonal
{
    protected TabPane $tabPane;

    public function __construct(TabPane &$tabPane)
    {
        $this->setTabPane($tabPane);
    }

    /**
     * @return TabPane
     */
    public function getTabPane(): TabPane
    {
        return $this->tabPane;
    }

    /**
     * @param TabPane $tabPane
     */
    public function setTabPane(TabPane &$tabPane): void
    {
        $this->tabPane = &$tabPane;
    }
}
