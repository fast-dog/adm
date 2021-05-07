<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\FileField;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\Resource\RelationResource;
use Dg482\Red\Resource\Resource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

/**
 * Class BaseResource
 * @package App\Models
 */
class BaseResource extends Resource
{
    /**
     * @param  string  $relation
     * @return RelationResource
     * @throws BindingResolutionException
     */
    public function hasOne(string $relation): RelationResource
    {
        $model = $this->getModel()->{$relation};

        $resource = (isset($this->relations[$relation]) && class_exists($this->relations[$relation])) ?
            new $this->relations[$relation] : app()->make(RelationResource::class);
        $resource->setRelation($relation);
        $resource->setContext($this->getContext());
        if ($model === null) {
            $model = $resource->getModel();
            $model = (new $model);
        }

        $resource->setModel($model);
        $resource->getAdapter()->setModel($model);

        return $resource;
    }

    /**
     * @param  string  $relation
     * @param  Field  $field
     * @return RelationResource
     * @throws BindingResolutionException
     * @throws BadVariantKeyException
     * @throws EmptyFieldNameException
     */
    public function hasMany(string $relation, Field &$field): RelationResource
    {
        $model = $this->getAdapter()->getModel();

        $field->setField($relation.'@'.$field->getField());

        /** @var Collection $collection */
        $collection = $model->{$relation};// relation HasMany
        if ($collection) {
            $relationModel = $collection->get(0);

            /** @var Resource|RelationResource $resource */
            $resource = app()->make($this->relations[$relation]);
            $resource->setRelation($relationModel);
            $resource->setContext($this->getContext());

            if (null === $relationModel) {
                $relationModel = $resource->getModel();
            }

            $resource->getAdapter()->setModel($relationModel);// set relation Model
            $resource->getAdapter()->getCommand()->setResult($collection->all());

            $resource->setCollection($collection ? ['total' => $collection->count()] : []);
        }

        $table = $resource->getTable(true);

        // prepare table data
        if ($field instanceof FileField) {
            if ($table['pagination']['total'] > 0) {
                array_map(function (array $file) use (&$field) {
                    $field->getValue()->push(new StringValue($file['id'], $file['path']));
                }, $table['data']);
            }
        }

        $resource->getAdapter()->setModel($model);// set self Model

        return $resource;
    }
}
