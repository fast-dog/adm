<?php

namespace FastDog\Adm\Listeners\User\Profile;

use FastDog\Adm\Events\User\Profile\CreateProfileTabSecurity;

/**
 * Class CreateProfileTabSecurityListeners
 * @package FastDog\Adm\Listeners\User\Profile
 */
class CreateProfileTabSecurityListeners
{
    /**
     * @param CreateProfileTabSecurity $event
     */
    public function handle(CreateProfileTabSecurity $event)
    {
        $tabPane = $event->getTabPane();
        // add form element to tabPane
        $event->setTabPane($tabPane);
    }
}
