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

        array_map(function (array $resource) use (&$result) {
        }, $user->getPermissionResource());

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

        event(new BeforeGetFrontendMenu($frontend));

        $adminMenu = (new MenuItem)
            ->setTitle('Администрирование')
            ->setIcon('setting')
            ->setRedirect('');

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

        return response()->json($frontend->getMenuItems());
    }
}
