<?php

namespace FastDog\Adm\Listeners\User\Profile;

use FastDog\Adm\Events\User\Profile\CreateProfileTabPersonal;

/**
 * Class CreateProfileTabPersonalListener
 * @package FastDog\Adm\Listeners\User\Profile
 */
class CreateProfileTabPersonalListener
{
    /**
     * @param CreateProfileTabPersonal $event
     */
    public function handle(CreateProfileTabPersonal $event)
    {
        $tabPane = $event->getTabPane();
        // add form element to tabPane
        $event->setTabPane($tabPane);
    }
}
