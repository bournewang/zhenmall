<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpertPolicy extends BasePolicy
{
    public $name = 'Expert';
}
