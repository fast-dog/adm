<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Builders\Menu\Frontend;
use Dg482\Red\Builders\Menu\MenuItem;
use Dg482\Red\Resource\Resource;
use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;
use FastDog\Adm\Events\AfterCreateFrontendMenu;
use FastDog\Adm\Events\BeforeCreateAdministrationMenu;
use FastDog\Adm\Events\BeforeGetFrontendMenu;
use FastDog\Adm\Events\GetUserInfo;
use FastDog\Adm\Models\User;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

/**
 * Class AdminController
 *
 * @package FastDog\Adm\Http\Controllers
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class AdminController extends BaseController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('FastDog\Adm\Http\Middleware\Admin');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $result = $user->getMe();

        event(new GetUserInfo($user, $result));

        $this->initRole($result);

        return response()->json($result);
    }

    /**
     * @param $result
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function initRole(&$result)
    {
        /** @var User $user */
        $user = auth()->user();

        $result['role'] = [
            'roleId' => $result['roleId']->first(),
            'name' => $user->name,
            'describe' => '',
            'status' => 1,
            'creatorId' => 'system',
            'deleted' => 0,
            'permissions' => $result['roleId']->map(function (string $role) use ($user) {
                return $user->getPermissionResource($role);
            }),
        ];

        $result['roleId'] = $result['roleId']->first();
        $result['success'] = true;
    }

    /**
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function role(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $result = $user->getMe();

        $this->initRole($result);

        return response()->json($result);
    }

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
                $alias = Str::lower($resource['idx']);
                $resourceMenu = (new MenuItem)
                    ->setTitle($resourceClass->getTitle())
                    ->setName($alias)
                    ->setComponent('resource/builder')// src/view/[resource/builder].vue
                    ->setIcon($resourceClass->getIcon())
                    ->setHref('/administration/'.$alias)
                    ->setMeta([
                        'id' => $alias,
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

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function resource(Request $request): JsonResponse
    {
        $result = [
            'success' => false,
        ];
        $alias = $request->get('alias');
        if ($alias) {
            /** @var Resource $resourceClass */
            $resourceClass = app()->get(Str::ucfirst($alias).'Resource');
            if ($resourceClass) {
                $result = [
                    'success' => true,
                    'table' => $resourceClass->getTable(),
                ];
            }
        }

        return response()->json($result);
    }
}
