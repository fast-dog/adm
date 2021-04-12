<?php

namespace FastDog\Adm;

use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;
use FastDog\Adm\Events\AfterCreateFrontendMenu;
use FastDog\Adm\Events\BeforeCreateAdministrationMenu;
use FastDog\Adm\Events\BeforeGetFrontendMenu;
use FastDog\Adm\Events\GetUserInfo;
use FastDog\Adm\Events\InitRoles;
use FastDog\Adm\Listeners\AfterCreateAdministrationMenuItemListener;
use FastDog\Adm\Listeners\AfterCreateFrontendMenuListeners;
use FastDog\Adm\Listeners\BeforeCreateAdministrationMenuListener;
use FastDog\Adm\Listeners\BeforeGetFrontendMenuListeners;
use FastDog\Adm\Listeners\GetUserInfoListeners;
use FastDog\Adm\Listeners\InitRolesListeners;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class AdmEventServiceProvider
 * @package FastDog\Adm
 */
class AdmEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AfterCreateFrontendMenu::class => [
            AfterCreateFrontendMenuListeners::class,
        ],
        AfterCreateAdministrationMenuItem::class => [
            AfterCreateAdministrationMenuItemListener::class,
        ],
        BeforeCreateAdministrationMenu::class => [
            BeforeCreateAdministrationMenuListener::class,
        ],
        BeforeGetFrontendMenu::class => [
            BeforeGetFrontendMenuListeners::class,
        ],
        InitRoles::class => [
            InitRolesListeners::class,
        ],
        GetUserInfo::class => [
            GetUserInfoListeners::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
