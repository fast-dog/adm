<?php

namespace FastDog\Adm;

use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;
use FastDog\Adm\Events\AfterCreateFrontendMenu;
use FastDog\Adm\Events\BeforeCreateAdministrationMenu;
use FastDog\Adm\Events\BeforeGetFrontendMenu;
use FastDog\Adm\Events\GetUserInfo;
use FastDog\Adm\Events\User\InitRoles;
use FastDog\Adm\Events\User\Profile\CreateProfileTabPersonal;
use FastDog\Adm\Events\User\Profile\CreateProfileTabs;
use FastDog\Adm\Events\User\Profile\CreateProfileTabSecurity;
use FastDog\Adm\Listeners\AfterCreateAdministrationMenuItemListener;
use FastDog\Adm\Listeners\AfterCreateFrontendMenuListeners;
use FastDog\Adm\Listeners\BeforeCreateAdministrationMenuListener;
use FastDog\Adm\Listeners\BeforeGetFrontendMenuListeners;
use FastDog\Adm\Listeners\GetUserInfoListeners;
use FastDog\Adm\Listeners\InitRolesListeners;
use FastDog\Adm\Listeners\User\Profile\CreateProfileTabPersonalListener;
use FastDog\Adm\Listeners\User\Profile\CreateProfileTabSecurityListeners;
use FastDog\Adm\Listeners\User\Profile\CreateProfileTabsListener;
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
        CreateProfileTabs::class => [
            CreateProfileTabsListener::class
        ],
        CreateProfileTabPersonal::class => [
            CreateProfileTabPersonalListener::class
        ],
        CreateProfileTabSecurity::class => [
            CreateProfileTabSecurityListeners::class
        ]
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
