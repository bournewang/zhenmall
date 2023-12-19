<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy extends BasePolicy
{
    public $name = 'Customer';
}
