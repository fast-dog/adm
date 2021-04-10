<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\AfterCreateFrontendMenu;

/**
 * Class AfterCreateFrontendMenuListeners
 * @package FastDog\Adm\Listeners
 */
class AfterCreateFrontendMenuListeners
{
    /**
     * @param  AfterCreateFrontendMenu  $event
     */
    public function handle(AfterCreateFrontendMenu $event)
    {
        $menuFrontend = $event->getMenu();
        // add menu item
    }
}
