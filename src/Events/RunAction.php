<?php


namespace FastDog\Adm\Events;

use Dg482\Red\Resource\Resource;

class RunAction
{
    /** @var array */
    protected array $result = [];

    /** @var Resource */
    protected Resource $resource;

    /**
     * RunSwitchAction constructor.
     * @param  array  $result
     * @param  Resource  $resource
     */
    public function __construct(array $result, Resource $resource)
    {
        $this->setResult($result);
        $this->setResource($resource);
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param  array  $result
     */
    public function setResult(array &$result): void
    {
        $this->result = &$result;
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
    public function setResource(Resource &$resource): void
    {
        $this->resource = &$resource;
    }
}
