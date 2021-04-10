<?php

namespace FastDog\Adm\Events;

use Illuminate\Support\Collection;

/**
 * Class InitRoles
 * @package FastDog\Adm\Events
 */
class InitRoles extends BaseEvent
{
    /** @var Collection */
    private Collection $roles;

    /**
     * InitRoles constructor.
     * @param Collection $roles
     */
    public function __construct(Collection &$roles)
    {
        $this->roles = &$roles;
    }

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }
}
