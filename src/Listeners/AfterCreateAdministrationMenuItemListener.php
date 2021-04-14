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
        $root = $event->getRoot();
        // add to root menu item

        $menuFrontend = $event->getMenu();
        // add administration menu item

        $resource = $event->getResources();
        // change resource list
    }
}
