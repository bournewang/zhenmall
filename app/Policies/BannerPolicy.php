<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy extends BasePolicy
{
    public $name = 'Banner';
}
