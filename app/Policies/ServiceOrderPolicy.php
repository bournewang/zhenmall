<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceOrderPolicy extends BasePolicy
{
    public $name = 'ServiceOrder';
}
