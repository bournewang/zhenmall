<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ValidatorHelper;
use App\Exceptions\ApiException;
use Carbon\Carbon;
use DB;

class Cart extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'carts';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'store_id',
        'user_id',
        'total_quantity',
        'total_price',
    ];    
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'total_quantity' => 'float',
        'total_price' => 'float',
    ];
    
    public static $rules = [
        'user_id' => 'integer|required',
        'total_quantity' => 'float|nullable',
        'total_price' => 'float|nullable',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function add($goods, $quantity = 1)
    {
        if ($exists = $this->goods()->find($goods->id)) {
            $quantity += $exists->pivot->quantity;
            $pivot = [
                'price' => $goods->price,
                'quantity' => $quantity,
                'subtotal' => $quantity * $goods->price
            ];
            $this->goods()->updateExistingPivot($goods, $pivot);
        }else{
            $pivot = [
                'price' => $goods->price,
                'quantity' => $quantity,
                'subtotal' => $quantity * $goods->price
            ];
            $this->goods()->attach($goods, $pivot);
        }
        $this->updated_at = Carbon::now(); // trigger observer
        $this->save(); 
    }
    
    public function change($goods, $quantity)
    {
        if ($exists = $this->goods()->find($goods->id)) {
            if ($quantity == 0) {
                $this->goods()->detach($goods);
            } else {
                $pivot = [
                    'price' => $goods->price,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $goods->price
                ];
                $this->goods()->updateExistingPivot($goods, $pivot);
            }
        }
        $this->updated_at = Carbon::now(); // trigger observer
        $this->save(); 
    }
    
    public function remove($goods)
    {
        if ($goods = $this->goods()->find($goods->id)) {
            $this->goods()->detach($goods);
            $this->updated_at = Carbon::now(); // trigger observer
            $this->save();
        }
    }
    
    public function clear()
    {
        $this->goods()->sync([]);
        $this->updated_at = Carbon::now();
        $this->save();
    }

    public function submit($address=null)
    {
        if (!$this->goods->first()){
            throw new ApiException(__('There is nothing in cart'), 400);
        }
        DB::beginTransaction();
        
        $data = [
            'store_id'  => $this->store_id,
            'user_id'   => $this->user_id,
            'order_no'  => Carbon::now()->format('YmdGis').rand(100000,999999),
            'amount'    => $this->total_price,
            'contact'       => $address->contact,
            'mobile'        => $address->mobile,
            'province_id'   => $address->province_id,
            'city_id'       => $address->city_id,
            'district_id'   => $address->district_id,
            'street'        => $address->street,
            'status'        => Order::CREATED
        ];
        if ($msg = ValidatorHelper::validate(Order::$rules, $data)){
            throw new ApiException($msg, 400);
        }
        $order = Order::create($data);
        
        // if ($order) {
        foreach ($this->goods as $good) {
            $subtotal = $good->pivot->subtotal;
            $order->goods()->attach($good->id, [
                'price' => $good->pivot->price,
                'quantity' => $good->pivot->quantity,
                'subtotal' => $subtotal,
            ]);
        }
        $order->save();
        $order->refresh();
        $this->clear();
        DB::commit();
        
        return $order;
    }
    
    public function detail()
    {
        $data = [];
        foreach ($this->goods as $good) {
            $info = $good->info();
            $data[] = [
                'goods_id' => $good->id,
                'name' => $info['name'],
                'thumb' => $info['thumb'] ?? null,
                'price' => $good->pivot->price,
                'quantity' => $good->pivot->quantity,
                'subtotal' => $good->pivot->subtotal
            ];
        }
        $info = parent::info();
        $info['items'] = $data;
        $info['total_price'] = round($this->total_price, 2);
        return $info;
    }
}
