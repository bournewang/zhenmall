<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy extends BasePolicy
{
    public $name = 'Category';
    
}
