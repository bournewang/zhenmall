<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy extends BasePolicy
{
    public $name = 'Cart';
}
