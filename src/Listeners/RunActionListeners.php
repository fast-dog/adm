<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\RunAction;
use Illuminate\Http\Request;

/**
 * Class RunActionListeners
 * @package FastDog\Adm\Listeners
 */
class RunActionListeners
{
    /** @var Request */
    protected Request $request;


    /**
     * RunSwitchActionListeners constructor.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param  RunAction  $event
     */
    public function handle(RunAction $event)
    {
        $result = $event->getResult();

        //....

        $event->setResult($result);
    }
}
