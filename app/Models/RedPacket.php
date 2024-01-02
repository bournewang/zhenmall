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
        'type',
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

    const TYPE_REGISTER = 'register';
    const TYPE_DAILY = 'daily';
    const TYPE_ORDER = 'ordering';

    static public function typeOptions()
    {
        return [
            self::TYPE_REGISTER => __(ucfirst(self::TYPE_REGISTER)),
            self::TYPE_DAILY    => __(ucfirst(self::TYPE_DAILY)),
            self::TYPE_ORDER    => __(ucfirst(self::TYPE_ORDER)),
        ];
    }

    public function typeLabel()
    {
        return self::typeOptions()[$this->type];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
