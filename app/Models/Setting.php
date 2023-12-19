<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $table = 'settings';

    public $fillable = [
        'banks',
        'device_types',
    ];

    protected $casts = [
        'banks' => 'json',
        'device_types' => 'json',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    static public function deviceTypes()
    {
        return self::first()->device_types;
    }
}
