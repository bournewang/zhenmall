<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    use HasFactory;
    
    protected $table = 'logistics';
    
    protected $fillable = [
        'name',
        'code',
        'img',
        'phone',
        'url',
        'note',
        'sort'
    ];
    
    static public function options()
    {
        $names = [];
        foreach(self::orderBy('sort', 'asc')->get() as $log){
            $names[$log->id] = $log->name . '/' . $log->code;
        }
        return $names;
    }
    
}
