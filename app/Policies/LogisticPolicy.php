<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogisticPolicy extends BasePolicy
{
    public $name = 'Logistic';
}
