<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RedPacket extends BaseModel
{
    use SoftDeletes;

    public $table = 'red_packets';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'amount',
        'open'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'float',
        'open' => 'boolean'
    ];

    protected $hidden = [
        'id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
