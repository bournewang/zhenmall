<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends BaseModel
{
    use HasFactory;
    
    public $table = 'stock_items';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'goods_id',
        'user_id',
        'stock_id',
        'order_id',
        'order_type',
        'quantity',
        'type'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'goods_id' => 'integer',
        'user_id' => 'integer',
        'stock_id' => 'integer',
        'order_id' => 'integer',
        'order_type' => 'string',
        'quantity' => 'float',
        'type' => 'string'
        
    ];

    protected $hidden = [
        'id',
    ];
    
    const PURCHASE = 'purchase';
    const SALE = 'sale';
    const OTHER = 'other';
    
    static public function typeOptions()
    {
        return [
            self::PURCHASE => __(ucfirst(self::PURCHASE)),
            self::SALE => __(ucfirst(self::SALE)),
            self::OTHER => __(ucfirst(self::OTHER))
        ];
    }
    
    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }   
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
    
    public function order()
    {
        return $this->morphTo(); //PurchaseOrder or SalesOrder
    }
}


