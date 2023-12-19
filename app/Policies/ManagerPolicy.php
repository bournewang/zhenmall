<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ManagerPolicy extends BasePolicy
{
    public $name = 'Manager';
}
