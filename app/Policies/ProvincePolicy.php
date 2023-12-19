<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProvincePolicy extends BasePolicy
{
    public $name = 'Province';
}
