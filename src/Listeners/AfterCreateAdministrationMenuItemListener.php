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
        $event->setRoot($root);

        $menuAdministration = $event->getMenu();
        // add administration menu item
        $event->setMenu($menuAdministration);

        $resource = $event->getResources();
        // change resource list
        $event->setResources($resource);
    }
}
