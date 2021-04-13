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

    /** @var array */
    private array $resources;

    /**
     * AfterCreateAdministrationMenuItem constructor.
     * @param  MenuItem  $menuItem
     * @param  array  $resources
     */
    public function __construct(MenuItem &$menuItem, array &$resources = [])
    {
        $this->menu = &$menuItem;
        $this->resources = &$resources;
    }

    /**
     * @return MenuItem
     */
    public function getMenu(): MenuItem
    {
        return $this->menu;
    }

    /**
     * @param  MenuItem  $menu
     */
    public function setMenu(MenuItem $menu): void
    {
        $this->menu = $menu;
    }


    /**
     * @return array
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * @param  array  $resources
     */
    public function setResources(array $resources): void
    {
        $this->resources = $resources;
    }
}
