<?php

namespace FastDog\Adm\Events;

use Dg482\Red\Builders\Menu\MenuItem;

/**
 * Class AfterCreateAdministrationMenuItem
 * @package FastDog\Adm\Events
 */
class AfterCreateAdministrationMenuItem extends BaseEvent
{
    /** @var MenuItem */
    private MenuItem $menu;

    public function __construct(MenuItem &$menuItem)
    {
        $this->menu = &$menuItem;
    }

    /**
     * @return MenuItem
     */
    public function getMenu(): MenuItem
    {
        return $this->menu;
    }
}
