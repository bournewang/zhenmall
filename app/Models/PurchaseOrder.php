<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class PurchaseOrder extends BaseModel
{
    use HasFactory;
    use ShipTrait;
    
    public $table = 'purchase_orders';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'order_no',
        'total_quantity',
        'total_price',
        'logistic_id',
        'waybill_number',
        'ship_status',
        'status',
        'items',
        'comment'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer', 
        'order_no' => 'string',
        'total_quantity' => 'float',
        'total_price' => 'float',
        'logistic_id' => 'integer',
        'waybill_number' => 'string',
        'ship_status' => 'integer',
        'status' => 'string',
        'items' => 'array',
        'comment' => 'string'
    ];
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            if (!$instance->order_no){
                $instance->order_no = new_order_no();
            }
        });
        static::updating(function($instance) {
        });
    }       
    const PURCHASING = 'purchasing';
    const SHIPPED = 'shipped';
    const COMPLETE = 'complete';    
    const CANCELED = 'canceled';
    
    static public function statusOptions()
    {
        return [
            self::PURCHASING    => __(ucfirst(self::PURCHASING)),
            self::SHIPPED       => __(ucfirst(self::SHIPPED)),
            self::COMPLETE      => __(ucfirst(self::COMPLETE)),
            self::CANCELED      => __(ucfirst(self::CANCELED)),
        ];
    }
    
    public function statusLabel()
    {
        return self::statusOptions()[$this->status];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function stockItems()
    {
        return $this->morphMany(StockItem::class, 'order');
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }    
    
    public function detail()
    {
        return $this->info();
    }  

    public function import()
    {
        $this->stockItems()->delete();
        foreach ($this->items as $item) {
            $data = $item['attributes']; 
            $goods_id = $data['goods_id'];
            $quantity = $data['quantity'];
            if (!$stock = Stock::where('store_id', $this->store_id)->where('goods_id',$goods_id)->first()){
                $stock = Stock::create([
                    'store_id' => $this->store_id,
                    'goods_id' => $goods_id,
                    'quantity' => $quantity
                ]);
            }
            
            StockItem::create([
                'stock_id' => $stock->id,
                'store_id' => $this->store_id,
                'goods_id' => $goods_id,
                'user_id' => Auth::user()->id,
                'order_id' => $this->id,
                'order_type' => self::class,
                'quantity' => $quantity,
                'type' => StockItem::PURCHASE
            ]);
            
            // else{
            $total = StockItem::where('store_id', $this->store_id)->where('goods_id',$goods_id)->sum('quantity');
            $stock->update(['quantity' => $total]);
            // }
        }
    }
}
