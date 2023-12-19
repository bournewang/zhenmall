<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class SalesOrder extends BaseModel
{
    use HasFactory;

    public $table = 'sales_orders';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'customer_id',
        'order_no',
        'total_quantity',
        'total_price',
        'paid_price',
        'status',
        'items',
        'comment'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'order_no' => 'string',
        'total_quantity' => 'float',
        'total_price' => 'float',
        'paid_price' => 'float',
        'items' => 'array',
        'status' => 'string',
        'comment' => 'string',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function stockItems()
    {
        return $this->morphMany(StockItem::class, 'order');
    }

    public function billItems()
    {
        return $this->morphMany(BillItem::class, 'order');
    }

    public function detail()
    {
        return $this->info();
    }

    public function export()
    {
        $this->stockItems()->delete();
        foreach ($this->items as $item) {
            $data = $item['attributes'];
            $goods_id = $data['goods_id'] ?? null;
            $quantity = $data['quantity'] ?? 0;
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
                'user_id' => Auth::user()->id ?? 1,
                'order_id' => $this->id,
                'order_type' => self::class,
                'quantity' => $quantity * -1,
                'type' => StockItem::SALE
            ]);

            // else{
            $total = StockItem::where('store_id', $this->store_id)->where('goods_id',$goods_id)->sum('quantity');
            $stock->update(['quantity' => $total]);
            // }
        }
    }
}


