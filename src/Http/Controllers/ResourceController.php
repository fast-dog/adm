<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Commands\Crud\Create;
use Dg482\Red\Commands\Crud\Update;
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
            $resourceClass = app()->get(Str::studly($alias).'Resource');
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
     * @param  FormSave  $request  form validation request
     * @return JsonResponse
     * @throws Exception
     */
    public function resourceFormSave(FormSave $request): JsonResponse
    {
        $result = [
            'success' => false,
            'form' => [],
        ];

        $resourceClass = $request->getResource();

        if ($resourceClass) {
            $formBackend = $request->getFormBackend();
            $values = $request->get('values', []);

            if (empty($values['id'])) {
                $cmd = (new Create)->setData($values);// 1.6 set data command
                $resourceClass->getAdapter()->setCommand($cmd);
                $result['success'] = $resourceClass->getAdapter()->write();// write new model
            } else {
                $update = array_diff_assoc($values, $formBackend['values']);// 1.2 diff update values
                if (!empty($update)) {
                    $cmd = (new Update)->setData($update);// 1.4.1 set data command
                    $resourceClass->getAdapter()->setCommand($cmd);
                    $result['success'] = $resourceClass->getAdapter()->update();// update exist values
                }
            }

            if ($result['success']) {
                $result['form'] = $resourceClass->getForm();
            }
        }

        return response()->json($result);
    }
}
