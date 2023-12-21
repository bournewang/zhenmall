<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawPolicy extends BasePolicy
{
    public $name = 'Withdraw';
}
