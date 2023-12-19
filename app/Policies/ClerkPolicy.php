<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClerkPolicy extends BasePolicy
{
    public $name = 'Clerk';
}
