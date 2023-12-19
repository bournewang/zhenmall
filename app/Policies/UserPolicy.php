<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class UserPolicy extends BasePolicy
{
    public $name = 'User';
}
