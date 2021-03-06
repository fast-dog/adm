<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\User\InitRoles;

/**
 * Class InitRolesListeners
 * @package FastDog\Adm\Listeners
 */
class InitRolesListeners
{
    /**
     * @param InitRoles $event
     */
    public function handle(InitRoles $event)
    {
        $roles = $event->getRoles();
        // add roles to collection
        $event->setRoles($roles);
    }
}
