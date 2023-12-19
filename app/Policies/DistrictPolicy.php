<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DistrictPolicy extends BasePolicy
{
    public $name = 'District';
}
