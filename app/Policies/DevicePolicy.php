<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy extends BasePolicy
{
    public $name = 'Device';
}
