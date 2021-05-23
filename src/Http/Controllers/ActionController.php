<?php

namespace FastDog\Adm\Http\Controllers;

use FastDog\Adm\Events\RunAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ActionController
 * @package FastDog\Adm\Http\Controllers
 */
class ActionController extends ResourceController
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

        event(new RunAction($result, $this->resource));

        return response()->json($result);
    }
}
