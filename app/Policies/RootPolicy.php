<?php
namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class RootPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)              {return $this->isRoot($user);}
    public function create($user)               {return $this->isRoot($user);}
    public function view($user, $model)         {return $this->isRoot($user);}
    public function update($user, $model)       {return $this->isRoot($user);}
    public function delete($user, $model)       {return $this->isRoot($user);}
    public function restore($user, $model)      {return $this->isRoot($user);}
    public function forceDelete($user, $model)  {return $this->isRoot($user);}
    
    private function isRoot($user)
    {
        return $user->hasRole(__('System Admin'));
    }
}
