<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'banners';

    protected $dates = ['deleted_at'];


    public $fillable = [
        // 'store_id',
        'goods_id',
        'title',
        'link',
        // 'image',
        'status'
    ];    
    
    protected $casts = [
        // 'store_id' => 'integer',
        'goods_id' => 'integer',
        'title' => 'string',
        'link' => 'string',
        // 'image' => 'string',
        'status' => 'string',
    ];
    
    public static $rules = [
        // 'store_id' => 'integer|required',
        // 'title' => 'string|required',
        // 'image' => 'string|required',
        // 'status' => 'string|required',
    ];
    
    
    protected static function beforesave(&$instance)
    {
        if ($instance->goods_id) {
            $instance->link = $instance->link ?? "/pages/goods/index?id=$instance->goods_id";
            // $instance->image = $instance->image ?? $instance->goods->img;
            $instance->title = $instance->title ?? $instance->goods->name;
        }
        // $instance->store_id = $instance->store_id ?? \Auth::user()->store_id;
    }
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            self::beforesave($instance);
        });
        static::updating(function ($instance) {
            self::beforesave($instance);
        });
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
    
    public function detail()
    {
        $info = parent::info();
        if ($m = $this->getMedia('main')->first()) {
            $info['image'] = $m->getUrl('large');
        }elseif ($m = $this->goods->getMedia('main')->first()){
            $info['image'] = $m->getUrl('large');
        }
        return $info;
    }
    
}
