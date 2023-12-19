<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends BaseModel
{
    use HasFactory;
    
    public $table = 'examples';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
    ];
    
    protected $casts = [
        'name' => 'string',
    ];

    protected $hidden = [
        'id',
    ];
    
}


