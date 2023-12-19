<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy extends BasePolicy
{
    public $name = 'Stock';
}
