<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Commands\Crud\Create;
use Dg482\Red\Commands\Crud\Delete;
use Dg482\Red\Commands\Crud\Read;
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

        if ($resourceClass = $this->getResource($request->get('alias', ''))) {
            $result = [
                'success' => true,
                'table' => $resourceClass->getTable(),
            ];
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

        if ($resourceClass = $this->getResource($request->get('alias', ''))) {
            $result = [
                'success' => true,
                'form' => $resourceClass->getForm(),
            ];
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
                    $update['id'] = $values['id'];
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

    /**
     * @param  FormSave  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function resourceDelete(Request $request): JsonResponse
    {
        $result = [
            'success' => false,
        ];

        if ($resourceClass = $this->getResource($request->get('alias', ''))) {
            $resourceClass->getAdapter()->read();// read model

            $resourceClass->getAdapter()->setCommand((new Delete));// set delete cmd
            $resourceClass->getAdapter()->delete();// execute delete method

            app()->request->merge(['id' => -1]);// reset id model

            $resourceClass->getAdapter()->setCommand((new Read));// new read cmd
            $result = [
                'success' => true,
                'table' => $resourceClass->getTable(),// get new table
            ];
        }

        return response()->json($result);
    }

    /**
     * @param  string  $alias
     * @return Resource|null
     */
    private function getResource(string $alias): ?Resource
    {
        return (!empty($alias)) ? app()->get(Str::studly($alias).'Resource') : null;
    }
}
