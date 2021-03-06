<?php

namespace FastDog\Adm\Resources\User;

use Dg482\Red\Builders\Form\BaseForms;
use Dg482\Red\Resource\Resource;
use FastDog\Adm\Models\BaseResource;
use FastDog\Adm\Models\User;
use FastDog\Adm\Resources\User\Forms\Identity;

/**
 * Class UserResource
 * @package App\Resources
 */
class UserResource extends BaseResource
{
    /**
     * @var string
     */
    protected string $resourceModel = User::class;

    /**
     * Отношения к профилю пользователя
     * @var string[]
     */
    protected array $relations = [
        'profile' => ProfileResource::class,
    ];

    /**
     * Правила валидации
     * @var array $validators
     */
    protected array $validators = [
        'password' => ['required', 'min:8'],
        'name' => ['required', 'max:50'],
        'email' => ['required', 'email', 'max:50'],
    ];

    /**
     * @param  string  $context
     * @return Resource
     */
    public function initResource(string $context = ''): Resource
    {
        $this->setTitle(trans('adm::resources.user.title'));
        $this->setIcon('team');

        $this->setLabels([
            'email' => trans('adm::resources.user.forms.identity.fields.email'),
            'name' => trans('adm::resources.user.forms.identity.fields.name'),
            'status' => trans('adm::resources.user.forms.identity.fields.status'),
            'role' => trans('adm::resources.user.forms.identity.fields.role'),
            'password' => trans('adm::resources.user.forms.identity.fields.password'),
        ]);

        // Скрытые поля
        $this->setHiddenFields([
            'email_verified_at',
            'verify_token',
            'remember_token',
            'online_at',
            'created_at',
            'updated_at',
            'two_factor_secret',
            'current_team_id',
            'profile_photo_path',
            'two_factor_recovery_codes',
        ]);

        $this->setContext(__CLASS__);

        return $this;
    }

    /**
     * Return form model
     *
     * @return BaseForms
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getFormModel(): BaseForms
    {
        if (!$this->formModel) {
            $this->setForm(app()->make(Identity::class));
        }

        return parent::getFormModel();
    }
}
