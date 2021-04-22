<?php

namespace FastDog\Adm\Http\Controllers;

use FastDog\Adm\Events\RunSwitchAction;
use Dg482\Red\Resource\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class SwitchActionController
 * @package FastDog\Adm\Http\Controllers
 */
class SwitchActionController extends BaseController
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function run(Request $request): JsonResponse
    {
        $result = [
            'success' => false,
        ];
        $alias = Arr::first(explode('/', $request->get('form', '')));

        if ($alias) {
            /** @var Resource $resourceClass */
            $resourceClass = app()->get(Str::ucfirst($alias).'Resource');

            if ($resourceClass) {
                event(new RunSwitchAction($result, $resourceClass));
            }
        }

        return response()->json($result);
    }
}
