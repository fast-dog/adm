<?php

namespace FastDog\Adm\Http\Controllers;

use Exception;
use FastDog\Adm\Http\Requests\FormSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Dg482\Red\Resource\Resource;

/**
 * Class ResourceController
 * @package FastDog\Adm\Http\Controllers
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class ResourceController extends BaseController
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

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function resourceFormSave(FormSave $request): JsonResponse
    {
        $result = [
            'success' => false,
        ];
        $resourceClass = $request->getResource();

        if ($resourceClass) {
            $formBackend = $request->getFormBackend();

            $update = array_diff_assoc($formBackend['values'], $request->get('values', []));// 1.2 diff update values

            if (!empty($update)) {// 1.3 if exist update values, validate data
                $resourceClass->getModel()->updateModel($request->all());
            }

            $result = [
                'success' => true,
                'form' => $resourceClass->getForm(),
            ];
        }

        return response()->json($result);
    }
}
