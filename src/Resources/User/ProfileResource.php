<?php

namespace FastDog\Adm\Resources\User;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Resource\RelationResource;
use FastDog\Adm\Resources\User\Forms\Profile;

/**
 * Class ProfileResource
 * @package FastDog\Adm\Resources\User
 */
class ProfileResource extends RelationResource
{
    /**
     * Return form model
     *
     * @return BaseForms
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getFormModel(): BaseForms
    {
        if (!$this->formModel) {
            $this->setForm(app()->make(Profile::class));
        }

        return parent::getFormModel();
    }
}
