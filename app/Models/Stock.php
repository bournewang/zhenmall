<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends BaseModel
{
    use HasFactory;
    
    public $table = 'stocks';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'goods_id',
        'quantity'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'goods_id' => 'integer',
        'quantity' => 'float',
    ];

    protected $hidden = [
        'id',
    ];
    
    protected $info_fields = ["id", "goods_id", "quantity"];
    
    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }   
    
    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }   
    
    public function detail()
    {
        $info = $this->info();
        if ($goods = $this->goods) {
            $cost = $goods->price_purchase * $this->quantity;
            $info = array_merge($info, [
                'goods_name' => $goods->name,
                'goods_img' => $goods->thumb(),
                'cost' => round($cost, 2),
                'cost_label' => money($cost)
            ]);
        }
        return $info;
    }
}


