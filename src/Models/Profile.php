<?php

namespace FastDog\Adm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use Illuminate\Queue\SerializesModels;

/**
 * Class Profile
 * @package App\Models\Users
 */
class Profile extends Model implements \Dg482\Red\Model
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    public $table = 'user_profile';

    /** @var array */
    public $fillable = [
        'user_id', 'birthday', 'website', 'company_id',
    ];

    public function updateModel(array $attributes, array $options = []): bool
    {
        return false;
    }

    public function storeModel(array $attributes, array $options = []): \Dg482\Red\Model
    {
        return $this;
    }
}
