<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\GetUserInfo;

/**
 * Class GetUserInfoListeners
 * @package FastDog\Adm\Listeners
 */
class GetUserInfoListeners
{
    /**
     * @param  GetUserInfo  $event
     */
    public function handle(GetUserInfo $event)
    {
        $user = $event->getUser();// auth user
        $result = $event->getResult(); // array $user->getMe();

        //....

        $event->setResult($result);
        $event->setUser($user);
    }
}
