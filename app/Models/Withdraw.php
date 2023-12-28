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

    const AUDITING = 'auditing';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';
    static public function statusOptions()
    {
        return [
            self::AUDITING  => __(ucfirst(self::AUDITING)),
            self::PROCESSING=> __(ucfirst(self::PROCESSING)),
            self::COMPLETED => __(ucfirst(self::COMPLETED)),
            self::REJECTED  => __(ucfirst(self::REJECTED))
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
        $info = parent::info();
        // $info['amount'] = money($this->amount);
        $info['status_label'] = $this->statusLabel();
        return $info;
    }

}
