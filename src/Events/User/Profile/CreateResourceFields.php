<?php

namespace FastDog\Adm\Events\User\Profile;

/**
 * Class CreateResourceFields
 * @package FastDog\Adm\Events\User\Profile
 */
class CreateResourceFields
{
    /** @var array */
    protected array $tabsResult;

    /**
     * CreateResourceFields constructor.
     * @param  array  $tabsResult
     */
    public function __construct(array &$tabsResult)
    {
        $this->tabsResult = $tabsResult;
    }

    /**
     * @return array
     */
    public function getTabsResult(): array
    {
        return $this->tabsResult;
    }

    /**
     * @param  array  $tabsResult
     */
    public function setTabsResult(array $tabsResult): void
    {
        $this->tabsResult = $tabsResult;
    }
}
