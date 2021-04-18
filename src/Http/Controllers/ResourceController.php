<?php

namespace FastDog\Adm\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Dg482\Red\Resource\Resource;

/**
 * Class ResourceController
 * @package FastDog\Adm\Http\Controllers
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class ResourceController
{
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

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function resourceForm(Request $request): JsonResponse
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
                    'form' => $resourceClass->getForm(),
                ];
            }
        }

        return response()->json($result);
    }
}
