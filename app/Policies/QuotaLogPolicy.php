<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotaLogPolicy extends BasePolicy
{
    public $name = 'QuotaLog';
}
