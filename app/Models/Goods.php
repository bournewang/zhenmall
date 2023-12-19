<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends BaseModel
{
    use SoftDeletes;
    use ShelfTrait;
    
    public $table = 'goods';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'qty', 
        'category_id',
        'supplier_id',
        'type',
        'brand',
        'price',
        'price_purchase',
        'price_ori',
        'detail',
        'status'
    ];
    
    protected $casts = [
        'name' => 'string',
        'qty' => 'string', 
        'category_id' => 'integer',
        'supplier_id' => 'integer',
        'type' => 'string',
        'brand' => 'string',
        'price' => 'float',
        'price_purchase'=> 'float',
        'detail' => 'string',
    ];
    public static $rules = [
        'name' => 'required|string',
        'category_id' => 'required',
        'price' => 'required'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price', 'subtotal');
    }
    
    public function thumb()
    {
        $main_img = $this->getMedia('main')->first();
        return $main_img ? $main_img->getUrl('thumb') : null;
    }
    public function info()
    {
        $main_img = $this->getMedia('main')->first();
        return array_merge(parent::info(),[
            'thumb' => $main_img ? $main_img->getUrl('thumb') : null,
        ]);
    }
    
    public function detail()
    {
        $imgs = [];
        $details = [];
        foreach ($this->getMedia('main') as $item) {
            $imgs[] = $item->getUrl('large');
        }
        
        foreach ($this->getMedia('detail') as $item) {
            $details[] = $item->getUrl('large');
        }
        return array_merge($this->info(),[
            'imgs' => $imgs,
            'details' => $details
        ]);
    }
}
