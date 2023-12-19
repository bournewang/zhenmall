<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy extends BasePolicy
{
    public $name = 'PurchaseOrder';
}
