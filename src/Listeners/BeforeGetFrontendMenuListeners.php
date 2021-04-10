<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\BeforeGetFrontendMenu;

/**
 * Class BeforeGetFrontendMenuListeners
 * @package FastDog\Adm\Listeners
 */
class BeforeGetFrontendMenuListeners
{
    /**
     * @param  BeforeGetFrontendMenu  $event
     */
    public function handle(BeforeGetFrontendMenu $event)
    {
        $menuAdmin = $event->getMenu();
        // add menu item
    }
}
