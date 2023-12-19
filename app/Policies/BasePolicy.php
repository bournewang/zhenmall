<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public $name;

    // public function viewAny($user)              {return $user->can(__('View') . __($this->name));    }
    // public function create($user)               {return $user->can(__('Create') . __($this->name));  }
    // public function view($user, $model)         {return $user->can(__('View') . __($this->name));    }
    // public function update($user, $model)       {return $user->can(__('Update') . __($this->name));  }
    // public function delete($user, $model)       {return $user->can(__('Delete') . __($this->name));  }
    // public function restore($user, $model)      {return $user->can(__('Restore') . __($this->name)); }
    // public function forceDelete($user, $model)  {return $user->can(__('ForceDelete') . __($this->name));}
    
    public function viewIndex($user)              {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('Index') . __($this->name));
        });
    }
    
    public function viewAny($user)              {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('View') . __($this->name));
        });
    }
    public function create($user)               {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('Create') . __($this->name));
        });
    }
    public function view($user, $model)         {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('View') . __($this->name));
        });    
    }
    public function update($user, $model)       {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('Update') . __($this->name));
        });
    }
    public function delete($user, $model)       {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('Delete') . __($this->name));
        });
    }
    public function restore($user, $model)      {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('Restore') . __($this->name));
        });
    }
    public function forceDelete($user, $model)  {
        $tag = tag_user($user);
        $key = implode('.', [$tag, __FUNCTION__, $this->name]);
        return cache1($tag, $key, function()use($user){
            return $user->can(__('ForceDelete') . __($this->name));
        });
    }
}
