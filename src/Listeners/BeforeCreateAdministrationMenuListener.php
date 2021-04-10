<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;

/**
 * Class BeforeCreateAdministrationMenuListener
 * @package FastDog\Adm\Listeners
 */
class BeforeCreateAdministrationMenuListener
{
    /**
     * @param  AfterCreateAdministrationMenuItem  $event
     */
    public function handle(AfterCreateAdministrationMenuItem $event)
    {
        $menuAdmin = $event->getMenu();
        // add menu item
    }
}
