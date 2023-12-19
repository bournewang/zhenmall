<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceRentalPolicy extends BasePolicy
{
    public $name = 'DeviceRental';
}
