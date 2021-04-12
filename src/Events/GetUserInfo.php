<?php

namespace FastDog\Adm\Events;

use FastDog\Adm\Models\User;

/**
 * Class GetUserInfo
 * @package FastDog\Adm\Events
 */
class GetUserInfo
{
    /** @var User */
    private User $user;

    /** @var array */
    private array $result;

    public function __construct(User &$user, array &$result)
    {
        $this->user = &$user;
        $this->result = &$result;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param  User  $user
     */
    public function setUser(User &$user): void
    {
        $this->user = &$user;
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
}
