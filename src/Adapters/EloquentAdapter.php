<?php

namespace FastDog\Adm\Adapters;

use Closure;
use Dg482\Red\Adapters\Adapter as DBAdapter;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Commands\Crud\Read;
use Dg482\Red\Model as DBModel;
use Exception;

use FastDog\Adm\LocalCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

/**
 * Class EloquentAdapter
 * @package App\Admin\Adapters
 */
class EloquentAdapter extends DBAdapter
{
    /** @var DBModel */
    protected DBModel $model;

    /** @var Request */
    protected Request $request;

    /** @var Closure */
    protected Closure $filter;

    /** @var array|string[] */
    protected array $columnTypes = [
        'id' => 'hidden',

    ];

    /**
     * EloquentAdapter constructor.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return DBModel
     */
    public function getModel(): DBModel
    {
        return $this->model;
    }

    /**
     * @param $model
     * @return DBAdapter
     * @throws Exception
     */
    public function setModel($model): DBAdapter
    {
        if (!$model instanceof DBModel) {
            throw new  Exception('Model not instance');
        }
        $this->model = $model;

        return $this;
    }

    /**
     * @return bool
     */
    public function readCmd(): bool
    {
        /** @var Read $cmd */
        $cmd = $this->getCommand();

        if (false === $cmd->isMultiple()) {
            $result = $this->model::where(function (Builder $query) {
                $this->where($query);
            })->first();
        } else {
            $result = $this->model::where(function (Builder $query) {
                $this->where($query);
            })->paginate($cmd->getPerPage());
        }

        $cmd->setResult($result);

        return true;
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    protected function where(Builder $query): Builder
    {
        $id = (int) $this->request->input('id', -1);

        if ($id > 0) {
            $query->where('id', $id);
        }

        if ($this->filter && $this->filter instanceof Closure) {
            $this->getFilter()($query);
        }

        return $query;
    }

    /**
     * @param  DBModel  $model
     * @param  array  $ignoreColumns
     * @return array
     * @throws BindingResolutionException
     */
    public function getTableColumns(DBModel $model, array $ignoreColumns = []): array
    {
        $result = [];// 1.0 result fields

        /**
         * @var EloquentModel $model
         */
        $model = $this->model; // 1.1 Eloquent Model
        $table = $model->getTable();// 1.2 get table name

        /** @var LocalCache $cache */
        $cache = app()->make(LocalCache::class);

        $builder = $model->getConnection()->getSchemaBuilder();

        array_map(function ($column) use (&$result, $builder, $table, $ignoreColumns) {
            if (is_string($column) && !in_array($column, $ignoreColumns)) {
                $result[$column] = [
                    'id' => $column,
                    'table' => $table,
                    'type' => 'AdmField'.Str::ucfirst($builder->getColumnType($table, $column)),// 1.4 get column type
                ];
            }
        }, $cache->withCache('schema-'.$table, function () use ($builder, $table) {
            return $builder->getColumnListing($table);// 1.3 get column list
        }, 'resource'));

        return $result;
    }

    /**
     * @return Closure
     */
    public function getFilter(): Closure
    {
        return $this->filter;
    }

    /**
     * @param  ?Closure  $filter
     * @return DBAdapter
     */
    public function setFilter(?Closure $filter): DBAdapter
    {
        $this->filter = ($filter instanceof Closure) ? $filter : function () {
        };
        return $this;
    }

    /**
     * Получение поля
     *
     * @param  array  $columnMeta
     * @return Field
     * @throws BindingResolutionException
     */
    public function getTableField(array $columnMeta): Field
    {
        if (app()->has($columnMeta['type'])) {
            /** @var Field $field */
            $field = app()->make($columnMeta['type']);
        } else {
            $field = app()->make('AdmFieldString');
            $field->setRequired()->setErrorMessages([
                'required' => 'Field :'.$columnMeta['type'].' not defined.',
            ]);
        }

        $field->setField($columnMeta['id']);

        return $field;
    }
}
