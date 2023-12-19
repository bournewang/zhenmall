<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy extends BasePolicy
{
    public $name = 'Setting';
}
