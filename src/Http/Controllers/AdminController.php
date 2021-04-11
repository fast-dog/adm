<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Builders\Menu\Frontend;
use Dg482\Red\Builders\Menu\MenuItem;
use Dg482\Red\Resource\Resource;
use FastDog\Adm\Events\AfterCreateAdministrationMenuItem;
use FastDog\Adm\Events\AfterCreateFrontendMenu;
use FastDog\Adm\Events\BeforeCreateAdministrationMenu;
use FastDog\Adm\Events\BeforeGetFrontendMenu;
use FastDog\Adm\Models\User;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\JsonResponse;
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
            ->setParentId(0)// set zero to parentId for item to push in the root router
            ->setName('dashboard')
            ->setTitle('Главная')
            ->setIcon('dashboard')
            ->setComponent('RouteView')
            ->setRedirect('/dashboard/workplace');

        $frontend->setMenu($dashboard);

        $workplace = (new MenuItem)
            ->setParentId($dashboard->getId()) // set parentId
            ->setComponent('Workplace')
            ->setName('workplace')
            ->setHref('/dashboard/workplace')
            ->setTitle('Рабочее место');// Vue component

        $frontend->setMenu($workplace);

        event(new BeforeGetFrontendMenu($frontend));

        $adminMenu = (new MenuItem)
            ->setName('administration')
            ->setTitle('Администрирование')
            ->setIcon('setting');

        $resources = $cacheManager->getStore()->get('FastDogAdmResources');

        event(new BeforeCreateAdministrationMenu($adminMenu));


        array_map(function (array $resource) use (&$adminMenu) {
            /** @var Resource $resourceClass */
            $resourceClass = app()->get($resource['idx'].'Resource');
            if ($resourceClass) {
                $resourceMenu = (new MenuItem)
                    ->setTitle($resourceClass->getTitle())
                    ->setComponent('/resource/builder')
                    ->setMeta([
                        'resource' => Str::lower($resource['idx']),
                    ]);
                $adminMenu->setChild($resourceMenu);
            }
        }, $resources);

        event(new AfterCreateAdministrationMenuItem($adminMenu));

        $frontend->setMenu($adminMenu);

        event(new AfterCreateFrontendMenu($frontend));

        return response()->json([
            'success' => true,
            'result' => $frontend->getMenuItems()
        ]);
    }
}
