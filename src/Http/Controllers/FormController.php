<?php

namespace FastDog\Adm\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class FormController
 * @package FastDog\Adm\Http\Controllers
 */
class FormController extends ResourceController
{
    /**
     * FormController constructor.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function fields(Request $request): JsonResponse
    {
        $result = [
            'success' => false,
            'items' => [],
        ];

        if ($this->resource) {
            $result['items'] = $this->resource->getFormModel()->resourceFields();
        } else {
            $result['error'] = 'Resource not defined or empty alias.';
        }

        return response()->json($result);
    }

    /**
     * @return JsonResponse
     */
    public function getFormStructure(): JsonResponse
    {
        $result = [
            'success' => false,
            'items' => [],
        ];


        return response()->json($result);
    }
}
