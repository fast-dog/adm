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
use Illuminate\Support\Facades\DB;
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
     * @param Request $request
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
     * @param Request $request
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
     * @param FormSave $request form validation request
     * @return JsonResponse
     * @throws Exception
     */
    public function resourceFormSave(FormSave $request): JsonResponse
    {
        $result = [
            'success' => false,
            'form' => [],
        ];
        /** @var Resource $resource */
        $resource = $request->getResource();

        if ($resource) {
            $formBackend = $request->getFormBackend();
            // get field specific value (field logic execution)
            $values = $resource->getFieldsValue($request->get('values', []));

            $command = $resource->getActionCommand($values);
            $command->setData($values);
            $resource->getAdapter()->setCommand($command);
            $method = 'execute';
            if ($command instanceof Create) {
                $method = 'write';
            } elseif ($command instanceof Update) {
                $update = array_diff_assoc($values, $formBackend['values']);// 1.2 diff update values
                if (!empty($update)) {
                    $update['id'] = $values['id'];
                    $command->setData($update);
                    $resource->getAdapter()->setCommand($command);
                    $method = 'update';
                }
            }

            DB::transaction(function () use ($resource, &$result, $values, $method) {
                $result['success'] = $resource->getAdapter()->{$method}();// write new model
                if ($result['success']) {
                    // exist relations
                    if (!empty($resource->getRelations())) {
                        $model = $resource->getAdapter()->getCommand()->getModel();

                        foreach ($resource->getRelations() as $idx => $relation) {
                            $relationValues = [];
                            foreach ($values as $name => $value) {
                                if (strpos($idx . '@', $name) !== false && !empty($value)) {
                                    $name = str_replace($idx . '@', '', $name);
                                    $relationValues[$idx][$name] = $value;
                                }
                            }
                            if (!empty($relationValues)) {
                                foreach ($relationValues as $relation => $values) {
                                    $values[$model->getTable() . '_id'] = $model->id;// set {owner_table}_id
                                    /** @var Resource $relationInstance relation resource */
                                    $relationInstance = $resource->getRelationInstance($relation);
                                    // set relation model
                                    $resource->getAdapter()->setModel($relationInstance->getModel());
                                    // get specific fields value (field logic execution)
                                    $values = $relationInstance->getFieldsValue($values);
                                    // update cmd data
                                    $resource->getAdapter()->getCommand()->setData($values);
                                    // write or update relation model
                                    $result['success'] = $resource->getAdapter()->{$method}();
                                }
                            }
                        }
                    }
                }
            });

            if ($result['success']) {
                $result['form'] = $resource->getForm();
            }
        }

        return response()->json($result);
    }

    /**
     * @param FormSave $request
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
     * @param string $alias
     * @return Resource|null
     */
    private function getResource(string $alias): ?Resource
    {
        return (!empty($alias)) ? app()->get(Str::studly($alias) . 'Resource') : null;
    }
}
