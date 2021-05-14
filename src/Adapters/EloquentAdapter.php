<?php

namespace FastDog\Adm\Adapters;

use Closure;
use Dg482\Red\Adapters\Adapter as DBAdapter;
use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Commands\Crud\Create;
use Dg482\Red\Commands\Crud\Delete;
use Dg482\Red\Commands\Crud\Read;
use Dg482\Red\Commands\Crud\Update;
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

    /** @var array $with relations */
    protected array $with = [];

    /**
     * EloquentAdapter constructor.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->filter = function (Builder $query) {
        };
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
     * @param  Builder  $query
     * @return Builder
     */
    protected function where(Builder $query): Builder
    {
        $id = (int) app()->request->input('id', -1);

        if ($id >= 0) {
            $query->where('id', $id);
        }

        if ($this->filter && $this->filter instanceof Closure) {
            $this->getFilter()($query, app()->request->all());
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

        /**  @var EloquentModel $model */
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
     * @param  array  $filters
     * @return DBAdapter
     */
    public function setFilters(array $filters): DBAdapter
    {
        $this->filter = function (Builder &$builder, array $request) use ($filters) {
            return array_map(function (Closure $closure) use (&$builder, $request) {
                return $closure($builder, $request);
            }, $filters);
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
        if (app()->has($columnMeta['type'])) {// 1.1 init Field
            /** @var Field $field */
            $field = app()->make($columnMeta['type']);
        } else {
            $field = app()->make('AdmFieldString');
            $field->setRequired()->setErrorMessages([
                'required' => 'Field :'.$columnMeta['type'].' not defined.',
            ]);
        }

        $field->setField($columnMeta['id']);// 1.2 set Field Name

        $result = $this->getCommand()->getResult();// 1.3 get cmd result

        if (!empty($result[$field->getField()])) {
            $field->setValue($result[$field->getField()]);// 1.4 set Field value
        }

        return $field;
    }

    /**
     * @param  int  $limit
     * @return array
     * @throws Exception
     */
    public function read($limit = 1): array
    {
        $result = [];

        /** @var Read $cmd */
        $cmd = $this->getCommand();
        $cmd->setMultiple($limit > 1);

        if (false === $cmd->isMultiple()) {
            $model = $this->model::where(function (Builder $query) {
                $this->where($query);
            })->with($this->with())->first();
            if ($model) {
                $this->setModel($model);
                $result = $model->toArray();
            }
            $cmd->setResult($result ?? []);
        } else {
            $result = $this->model::where(function (Builder $query) {
                $this->where($query);
            })->with($this->with())->paginate($cmd->getPerPage());
//            $this->setModel($result);
            $cmd->setResult($result->items());
            $result = $result->toArray();
        }

        return $result;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function write(): bool
    {
        /** @var Create $cmd */
        $cmd = $this->getCommand();
        if (!$cmd instanceof Create) {
            throw new Exception('Command not Create instance');
        }
        $cmd->setModel($this->getModel());

        return $cmd->execute();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function update(): bool
    {
        /** @var Update $cmd */
        $cmd = $this->getCommand();
        if (!$cmd instanceof Update) {
            throw new Exception('Command not Update instance');
        }
        $cmd->setModel($this->getModel());

        return $cmd->execute();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        /** @var Delete $cmd */
        $cmd = $this->getCommand();
        if (!$cmd instanceof Delete) {
            throw new Exception('Command not Delete instance');
        }
        $cmd->setModel($this->getModel());

        return $cmd->execute();
    }
}
