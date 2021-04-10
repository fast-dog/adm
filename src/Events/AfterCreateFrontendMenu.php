<?php

namespace FastDog\Adm\Events;

use Dg482\Red\Builders\Menu\Frontend;

/**
 * Class AfterCreateFrontendMenu
 * @package FastDog\Adm\Events
 */
class AfterCreateFrontendMenu extends BaseEvent
{
    /** @var Frontend */
    private Frontend $menu;

    public function __construct(Frontend &$menuItem)
    {
        $this->menu = &$menuItem;
    }

    /**
     * @return Frontend
     */
    public function getMenu(): Frontend
    {
        return $this->menu;
    }
}
