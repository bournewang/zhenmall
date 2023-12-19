<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesmanPolicy extends BasePolicy
{
    public $name = 'Salesman';
}
