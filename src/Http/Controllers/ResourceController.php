<?php

namespace FastDog\Adm\Http\Controllers;

use Dg482\Red\Commands\Crud\Command;
use Dg482\Red\Commands\Crud\Create;
use Dg482\Red\Commands\Crud\Delete;
use Dg482\Red\Commands\Crud\Read;
use Dg482\Red\Commands\Crud\Update;
use Exception;
use FastDog\Adm\Http\Requests\FormSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
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
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
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
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
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
            'success' => true,
            'form' => [],
        ];
        /** @var Resource $resource */
        $resource = $request->getResource();

        if ($resource) {
            $formBackend = $request->getFormBackend();
            // get field specific value (field logic execution)
            $values = $resource->getFieldsValue(
                array_merge($request->get('values', []), Arr::get($request->files->all(), 'values', []))
            );
            /** @var Command $command */
            $command = $resource->getActionCommand($values);
            $command->setData($values);
            $resource->getAdapter()->setCommand($command);
            $method = 'execute';
            if ($command instanceof Create) {
                $method = 'write';
            } elseif ($command instanceof Update) {
                $method = 'update';
            }

            DB::transaction(function () use ($resource, &$result, $values, $method) {
                $result['success'] = $resource->getAdapter()->{$method}();// write new model
                // exist relations
                if (!empty($resource->getRelations())) {
                    $model = $resource->getAdapter()->getCommand()->getModel();
                    foreach ($resource->getRelations() as $idx => $relation) {
                        $relationValues = [];
                        foreach ($values as $name => $value) {
                            if (strpos($name, $idx.'@') !== false && !empty($value)) {
                                $name = str_replace($idx.'@', '', $name);
                                $relationValues[$idx][$name] = $value;
                            }
                        }
                        if (!empty($relationValues)) {
                            foreach ($relationValues as $relation => $values) {
                                $values[$model->getTable().'_id'] = $model->id;// set {owner_table}_id
                                /** @var Resource $relationInstance relation resource */
                                $relationInstance = $resource->getRelationInstance($relation);
                                if (null === $relationInstance) {
                                    $resource->setRelationInstance($relation, app()->make(
                                        $resource->getRelations()[$relation]
                                    ));
                                    $relationInstance = $resource->getRelationInstance($relation);
                                }

                                // set relation model
                                $resource->getAdapter()->setModel($relationInstance->getModel());
                                // get specific fields value (field logic execution)
                                $values = $relationInstance->getFieldsValue($values);

                                // update cmd data
                                $cmd = $resource->getAdapter()->getCommand();
                                if (method_exists($cmd, 'setData')) {
                                    $cmd->setData($values);
                                }
                                // write or update relation model
                                $resource->getAdapter()->{$method}();
                            }
                        }
                    }
                }
            });

            if ($result['success']) {
                $result['form'] = $resource->getForm();
            }
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
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

        if ($resource = $this->getResource($request->get('alias', ''))) {
            $resource->getAdapter()->read();// read model

            $resource->getAdapter()->setCommand((new Delete));// set delete cmd
            $resource->getAdapter()->delete();// execute delete method

            app()->request->merge(['id' => -1]);// reset id model

            $resource->getAdapter()->setCommand((new Read));// new read cmd
            $result = [
                'success' => true,
                'table' => $resource->getTable(),// get new table
            ];
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
        }

        return response()->json($result);
    }

    /**
     * @param  string  $alias
     * @return Resource|null
     */
    protected function getResource(string $alias): ?Resource
    {
        return (!empty($alias)) ? app()->get(Str::studly($alias).'Resource') : null;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function resourceAssetsDelete(Request $request): JsonResponse
    {
        $result = [
            'success' => false,
        ];

        if ($resource = $this->getResource($request->get('alias', ''))) {
            if ($storage = $resource->getAssets()) {
                $storage->get($request->get('id'));
                $result['success'] = $storage->remove();
            }
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
        }

        return response()->json($result);
    }
}
