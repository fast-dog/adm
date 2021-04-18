<?php

namespace FastDog\Adm\Http\Controllers;

use FastDog\Adm\Events\GetUserInfo;
use FastDog\Adm\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

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
     * @throws BindingResolutionException
     */
    public function info(): JsonResponse
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
     * @throws BindingResolutionException
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
}
