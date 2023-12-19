<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy extends BasePolicy
{
    public $name = 'Review';
}
