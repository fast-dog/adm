<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;

/**
 * Class AfterCreateAdministrationMenuItemListener
 * @package FastDog\Adm\Listeners
 */
class AfterCreateAdministrationMenuItemListener
{
    /**
     * @param  AfterCreateAdministrationMenuItem  $event
     */
    public function handle(AfterCreateAdministrationMenuItem $event)
    {
        $menuFrontend = $event->getMenu();
        // add menu item
    }
}
