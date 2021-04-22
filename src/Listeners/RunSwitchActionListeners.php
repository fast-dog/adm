<?php

namespace FastDog\Adm\Listeners;

use FastDog\Adm\Events\RunSwitchAction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Fortify\RecoveryCode;

/**
 * Class RunSwitchActionListeners
 * @package FastDog\Adm\Listeners
 */
class RunSwitchActionListeners
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
     * @param  RunSwitchAction  $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(RunSwitchAction $event)
    {
        $result = $event->getResult();
        $checked = (bool) $this->request->get('checked');
        switch ($this->request->get('action')) {
            case 'two-factor-authentication-enabled':
                if ($checked) {
                    $provider = app()->make(\Laravel\Fortify\TwoFactorAuthenticationProvider::class);
                    $this->request->user()->forceFill([
                        'two_factor_secret' => encrypt($provider->generateSecretKey()),
                        'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                            return RecoveryCode::generate();
                        })->all())),
                    ])->save();
                } else {
                    $this->request->user()->forceFill([
                        'two_factor_secret' => null,
                        'two_factor_recovery_codes' => null,
                    ])->save();
                }
                break;
            default:
                break;
        }
    }
}
