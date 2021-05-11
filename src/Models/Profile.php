<?php

namespace FastDog\Adm\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App\Models\Users
 */
class Profile extends Model
{
    /** @var bool  */
    public $timestamps = false;

    /** @var string */
    public $table = 'user_profile';

    /** @var array */
    public $fillable = [
        'user_id', 'birthday', 'website', 'company_id',
    ];
}
