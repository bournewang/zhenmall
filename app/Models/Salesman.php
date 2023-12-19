<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends User
{
    use HasFactory;
    
    protected $table = 'users';
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            $instance->type = User::SALESMAN;
        });
    }
}
