<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Fields\FileField;
use Dg482\Red\Builders\Form\Fields\Values\StringValue;
use Dg482\Red\Builders\Form\Structure\BaseStructure;
use Dg482\Red\Exceptions\BadVariantKeyException;
use Dg482\Red\Exceptions\EmptyFieldNameException;
use Dg482\Red\Resource\RelationResource;
use Dg482\Red\Resource\Resource;
use Exception;
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
     * @param  BaseStructure  $field
     * @return RelationResource
     * @throws BindingResolutionException
     */
    public function hasOne(string $relation, BaseStructure &$field): RelationResource
    {
        $model = $this->getAdapter()->getModel();
        $this->setModel($model);

        $field->setField($relation.'@'.$field->getField());

        $relationModel = $model->{$relation};

        /** @var RelationResource $resource */
        $resource = (isset($this->relations[$relation]) && class_exists($this->relations[$relation])) ?
            app()->make($this->relations[$relation]) : app()->make(RelationResource::class);

        $resource->setContext($this->getContext());
        if ($relationModel) {
            $resource->setRelation($relationModel);
            $resource->setContext($this->getContext());
            $resource->setModel($relationModel);

            $resource->getAdapter()->setModel($relationModel);// set relation data in Adapter
            $resource->getAdapter()->getCommand()->setResult((array) $relationModel ?? []);

            if (method_exists($field, 'setFieldRelation')) {
                $field->setFieldRelation($this->getModel(), $relationModel);// set relation data in Field
            }

            $field->setItems($resource->formModel->resourceFields());
        }
        $this->getAdapter()->setModel($model);// set self Model

        $this->setRelationInstance($relation, $resource);// set self relation instance

        return $resource;
    }

    /**
     * @param  string  $relation
     * @param  Field|null  $field
     * @return RelationResource
     * @throws BadVariantKeyException
     * @throws BindingResolutionException
     * @throws EmptyFieldNameException
     * @throws Exception
     */
    public function hasMany(string $relation, Field &$field = null): RelationResource
    {
        $model = $this->getAdapter()->getModel();

        $field->setField($relation.'@'.$field->getField());

        /** @var Collection $collection */
        $collection = $model->{$relation};// relation HasMany

        /** @var Resource|RelationResource $resource */
        $resource = app()->make($this->relations[$relation]);

        if ($collection) {
            $relationModel = $collection->get(0);

            $resource->setRelation($relationModel);
            $resource->setContext($this->getContext());

            if ($this->getAssets()) {
                $resource->setAssets($this->getAssets());
            }

            if (null === $relationModel) {
                $relationModel = $resource->getModel();
            }

            $resource->getAdapter()->setModel($relationModel);// set relation Model in Adapter
            $resource->getAdapter()->getCommand()->setResult($collection->all());

            $resource->setCollection(['total' => $collection->count()]);

            if (method_exists($field, 'setFieldRelation')) {
                $field->setFieldRelation($model, $relationModel);// set relation data in Field
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

            $resource->setFields([$field]);
        }

        $resource->getAdapter()->setModel($model);// set self Model

        $this->setRelationInstance($relation, $resource);// set self relation instance

        return $resource;
    }
}
