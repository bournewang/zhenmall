<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class GoodsPolicy extends BasePolicy
{
    public $name = 'Goods';
}

