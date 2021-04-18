<?php

namespace FastDog\Adm\Http\Controllers\User;

use Exception;
use FastDog\Adm\Resources\User\Forms\Profile;
use FastDog\Adm\Resources\User\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Profile
 * @package FastDog\Adm\Http\Controllers\User
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class ProfileController extends BaseController
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $result = ['success' => false];

        /** @var ProfileResource $resource */
        $resource = app()->get(ProfileResource::class);
        /** @var Profile $formModel */
        $formModel = app()->get(Profile::class);
        $resource->setForm($formModel);

        try {
            $result['success'] = true;
            $result['form'] = $resource->getForm();
        } catch (Exception $exception) {
            $result['code'] = $exception->getCode();
            $result['message'] = $exception->getMessage();
        }

        return response()->json($result);
    }
}
