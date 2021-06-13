<?php

namespace FastDog\Adm\Models;

use Dg482\Red\Builders\Form\Fields\Field;
use Dg482\Red\Builders\Form\Structure\BaseStructure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use Dg482\Red\Resource\Resource;

/**
 * Class FormStructure
 * @package FastDog\Adm\Models
 */
class FormStructure extends Model
{
    use NodeTrait;

    /** @var string */
    protected $table = 'form_structure';

    /** @var string */
    private string $alias = '';

    /** @var array */
    public $fillable = ['name', 'type', 'resource', 'attributes'];

    /**
     * @return string[]
     */
    protected function getScopeAttributes()
    {
        return ['resource'];
    }

    /**
     * @param  string  $resource
     * @return FormStructure|null
     */
    protected function getRoot(string $resource): ?FormStructure
    {
        return self::where([
            'resource' => $resource,
            self::getLftName() => 1,
        ])->first();
    }


    /**
     * @param  string  $alias
     * @return array
     * @throws Exception
     */
    public function getResourceStructure(string $alias): array
    {
        $this->alias = Str::studly($alias);

        /** @var Resource $resource */
        $resource = app()->get($this->alias.'Resource');
        /** @var self $root */
        $root = $this->getRoot($this->alias);
        $result = [];
        $resourceFields = $resource->getFormModel()->resourceFields();
        if (!$root) {
            $parent = self::create([
                'name' => 'Root',
                'type' => 'root',
                'resource' => $this->alias,
            ]);
            array_map(function (Field $field) use ($parent) {
                $id = $field->getField();
                $node = self::create([
                    'name' => $field->getField(),
                    'type' => $field->getFieldType(),
                    'resource' => $this->alias,
                ]);
                $parent->appendNode($node);
                if ($field instanceof BaseStructure) {
                    $this->buildStructureFromFields($field->getItems(), $node);
                }
            }, $resourceFields);
        }

        $root = $this->getRoot($this->alias);


        $traverse = function ($fields, &$result = []) use (&$traverse) {
            foreach ($fields as $field) {
                $data = [
                    'field' => $field->name,
                ];
                if ($field->children->count() > 0) {
                    $data['children'] = [];
                    $traverse($field->children, $data['children']);
                }
                $result[] = $data;
            }
        };

        $traverse($root->children()->get(), $result);

        $resource->getFormModel()->setFormStructure($result);


        return array_map(function (Field $field) {
            return $field->getFormField(true);
        }, $resource->getFormModel()->sortFields($resourceFields));
    }

    /**
     * @param  array  $items
     * @param  FormStructure  $parent
     * @throws \Dg482\Red\Exceptions\EmptyFieldNameException
     */
    protected function buildStructureFromFields(array $items, FormStructure $parent)
    {
        array_map(function (Field $field) use (&$result, $parent) {
            $id = $field->getField();
            $node = self::create([
                'name' => $field->getField(),
                'type' => $field->getFieldType(),
                'resource' => $this->alias,
            ]);
            $parent->appendNode($node);
            if ($field instanceof BaseStructure) {
                $this->buildStructureFromFields($field->getItems(), $node);
            }
        }, $items);
    }
}
