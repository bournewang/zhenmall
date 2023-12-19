<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockItemPolicy extends BasePolicy
{
    public $name = 'StockItem';
}
