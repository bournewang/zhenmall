<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HealthPolicy extends BasePolicy
{
    public $name = 'Health';
}
