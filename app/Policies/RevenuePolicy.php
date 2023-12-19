<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevenuePolicy extends BasePolicy
{
    public $name = 'Revenue';
}
