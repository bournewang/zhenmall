<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends BaseModel
{
    use HasFactory;

    public $table = 'withdraw';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'amount',
        'status'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'amount' => 'float',
        'status' => 'string'
    ];

    protected $hidden = [
        'id',
    ];

    const GRANT = 'grant';
    const REJECT = 'reject';
    static public function statusOptions()
    {
        return [
            self::GRANT => __(ucfirst(self::GRANT)),
            self::REJECT => __(ucfirst(self::REJECT))
        ];
    }

    public function statusLabel()
    {
        return self::statusOptions()[$this->status] ?? '-';
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        return [
            'amount' => money($this->amount),
            'status_label' => $this->statusLabel()
        ];
    }

}
