<?php

namespace FastDog\Adm\Listeners\User\Profile;

use FastDog\Adm\Events\User\Profile\CreateProfileTabs;

/**
 * Class CreateProfileTabsListener
 * @package FastDog\Adm\Listeners\User\Profile
 */
class CreateProfileTabsListener
{
    /**
     * @param CreateProfileTabs $event
     */
    public function handle(CreateProfileTabs $event)
    {
        $tabs = $event->getTabs();
        // add tabPane to collection
        $event->setTabs($tabs);
    }
}
