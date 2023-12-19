<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends BaseModel
{
    use HasFactory;
    public $table = 'reviews';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'order_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'rating' => 'integer',
        'comment' => 'string',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            $instance->store_id = $instance->order->store_id;
            $instance->user_id = $instance->order->user_id;
        });
        static::updating(function ($instance) {
            // self::beforesave($instance);
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function info()
    {
        return array_merge(parent::info(), [
            'user_img' => $this->user->avatar,
            'nickname' => $this->user->nickname,
        ]);
    }

    public function detail()
    {
        return array_merge($this->info(),[
            'imgs' => $this->getMediaData('photo'),
            'order' => $this->order->detail()
        ]);
    }
}
