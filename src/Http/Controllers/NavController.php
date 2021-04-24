<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Builders\Menu\Frontend;
use Dg482\Red\Builders\Menu\MenuItem;
use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;
use FastDog\Adm\Events\AfterCreateFrontendMenu;
use FastDog\Adm\Events\BeforeCreateAdministrationMenu;
use FastDog\Adm\Events\BeforeGetFrontendMenu;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

/**
 * Class NavController
 * @package FastDog\Adm\Http\Controllers
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class NavController extends BaseController
{
    /**
     * @param  CacheManager  $cacheManager
     * @return JsonResponse
     */
    public function nav(CacheManager $cacheManager): JsonResponse
    {
        $frontend = new Frontend();

        $dashboard = (new MenuItem)
            ->setId(1)
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setName('dashboard')
            ->setTitle(trans('adm::adm.home'))
            ->setIcon('dashboard')
            ->setComponent('RouteView')
            ->setRedirect('/dashboard/workplace');

        $frontend->setMenu($dashboard);

        $workplace = (new MenuItem)
            ->setParentId($dashboard->getId()) // set parentId
            ->setComponent('Workplace')// Vue component
            ->setName('workplace')
            ->setIcon('desktop')
            ->setHref('/dashboard/workplace')
            ->setTitle(trans('adm::adm.workplace'));

        $frontend->setMenu($workplace);

        event(new BeforeGetFrontendMenu($frontend));

        $adminMenu = (new MenuItem)
            ->setName('administration')
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setTitle(trans('adm::adm.administration'))
            ->setComponent('RouteView')
            ->setRedirect('resource/builder')
            ->setIcon('appstore');

        $resources = $cacheManager->getStore()->get('FastDogAdmResources');

        event(new BeforeCreateAdministrationMenu($frontend, $adminMenu, $resources));

        array_map(function (array $resource) use (&$adminMenu, &$frontend) {
            /** @var Resource $resourceClass */
            $resourceClass = app()->get($resource['idx'].'Resource');
            if ($resourceClass) {
                $alias = Str::lower(Str::kebab($resource['idx']));
                $resourceMenu = (new MenuItem)
                    ->setTitle($resourceClass->getTitle())
                    ->setName($alias)
                    ->setComponent('resource/builder')// src/view/[resource/builder].vue
                    ->setIcon($resourceClass->getIcon())
                    ->setHref('/administration/'.$alias)
                    ->setMeta([
                        'resource' => $alias,
                    ])
                    ->setParentId($adminMenu->getId());

                $frontend->setMenu($resourceMenu);

                $adminMenu->setChild($resourceMenu);
            }
        }, $resources);

        event(new AfterCreateAdministrationMenuItem($frontend, $adminMenu));

        $frontend->setMenu($adminMenu);

        $setting = (new MenuItem)
            ->setName('setting')
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setTitle(trans('adm::adm.setting'))
            ->setComponent('RouteView')
            ->setIcon('setting');

        $frontend->setMenu($setting);

        $userProfile = (new MenuItem)
            ->setParentId($setting->getId())
            ->setName('account')
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setTitle(trans('adm::adm.account'))
            ->setComponent('account/center')
            ->setIcon('user');

        $setting->setChild($userProfile);
        $frontend->setMenu($userProfile);

        $userProfileSetting = (new MenuItem)
            ->setParentId($setting->getId())
            ->setName('account-setting')
            ->setTitle(trans('adm::adm.account_setting'))
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setMeta([
                'show' => false,
            ])
            ->setComponent('account/setting')
            ->setIcon('user');

        $setting->setChild($userProfileSetting);
        $frontend->setMenu($userProfileSetting);

        event(new AfterCreateFrontendMenu($frontend));

        return response()->json([
            'success' => true,
            'result' => $frontend->getMenuItems(),
        ]);
    }
}
