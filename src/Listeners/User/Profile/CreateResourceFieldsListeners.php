<?php

namespace FastDog\Adm\Listeners\User\Profile;

use FastDog\Adm\Events\User\Profile\CreateResourceFields;

/**
 * Class CreateResourceFieldsListeners
 * @package FastDog\Adm\Listeners\User\Profile
 */
class CreateResourceFieldsListeners
{
    /**
     * @param  CreateResourceFields  $event
     */
    public function handle(CreateResourceFields $event)
    {
        $result = $event->getTabsResult();
        // add tabs or fields to result form
        $event->setTabsResult($result);
    }
}
