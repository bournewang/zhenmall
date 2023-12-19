<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesOrderPolicy extends BasePolicy
{
    public $name = 'SalesOrder';
}
