<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceLog extends BaseModel
{
    use SoftDeletes;

    public $table = 'balance_log';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'type',
        'amount',
        'balance',
        'comment',
        'open'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'type' => 'string',
        'amount' => 'float',
        'balance' => 'float',
        'comment' => 'string',
        'open' => 'boolean'
    ];

    protected $hidden = [
        'id',
    ];

    const DEPOSIT = 'deposit';
    const CONSUME = 'consume';
    const EDIT = 'edit';
    static public function typeOptions()
    {
        return [
            self::DEPOSIT => __(ucfirst(self::DEPOSIT)),
            self::CONSUME => __(ucfirst(self::CONSUME)),
            self::EDIT => __(ucfirst(self::EDIT))
        ];
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
