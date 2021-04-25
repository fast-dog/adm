<?php

namespace FastDog\Adm\Http\Requests;

use Dg482\Red\Exceptions\EmptyFieldNameException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Dg482\Red\Resource\Resource;

/**
 * Class FormSave
 * @package FastDog\Adm\Http\Requests
 */
class FormSave extends FormRequest
{
    /** @var array */
    private array $message = [];

    /** @var array */
    private array $rules = [];

    /** @var Resource */
    private Resource $resource;

    /** @var array */
    private array $formBackend;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws EmptyFieldNameException
     */
    public function authorize()
    {
        $alias = Arr::first(explode('/', $this->request->get('form', '')));

        if ($alias) {
            app()->request->merge([
                'alias' => $alias,
            ]);
            /** @var Resource $resourceClass */
            $resourceClass = app()->get(Str::studly($alias).'Resource');

            if ($resourceClass) {
                $this->setResource($resourceClass);

                $formBackend = $resourceClass->getForm(false);// 1.1 get form with backend validators

                $this->setFormBackend($formBackend);

                foreach ($formBackend['validator'] as $idx => $rule) {
                    $this->rules['values.'.$idx] = $rule;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * @return Resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }

    /**
     * @param  Resource  $resource
     */
    public function setResource(Resource $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function getFormBackend(): array
    {
        return $this->formBackend;
    }

    /**
     * @param  array  $formBackend
     */
    public function setFormBackend(array $formBackend): void
    {
        $this->formBackend = $formBackend;
    }
}
