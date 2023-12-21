<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ExpressHelper;

class Order extends BaseModel
{
    use SoftDeletes;
    use AddressTrait;
    use ShipTrait;

    public $table = 'orders';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'user_id',
        'order_no',
        'amount',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'contact',
        'mobile',
        'status',
        'logistic_id',
        'waybill_number',
        'ship_status',
        'paid_at',
        'refund_at'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_no' => 'string',
        'amount' => 'float',
        'mobile' => 'string',
        'contact' => 'string',
        'province_id' => 'integer',
        'city_id'  => 'integer',
        'district_id'=> 'integer',
        'street' => 'string',
        'paid_at' => 'datetime',
        'refund_at' => 'datetime',
    ];

    public static $rules = [
        'contact' => 'required|string|max:12',
        'mobile' => 'required|string|max:16',
        'order_no' => 'required|string|max:24',
        'amount' => 'required|numeric|min:0.01'
    ];

    const CREATED = 'unpaid';
    const PAID = 'paid';
    const SHIPPED = 'shipped';
    const COMPLETE = 'complete';
    const REVIEWED = 'reviewed';
    const CANCELED = 'canceled';

    static public function statusOptions()
    {
        return [
            self::CREATED   => __(ucfirst(self::CREATED)),
            self::PAID      => __(ucfirst(self::PAID)),
            self::SHIPPED   => __(ucfirst(self::SHIPPED)),
            self::COMPLETE  => __(ucfirst(self::COMPLETE)),
            self::REVIEWED  => __(ucfirst(self::REVIEWED)),
            self::CANCELED  => __(ucfirst(self::CANCELED)),
        ];
    }

    static public function validStatus()
    {
        return [
            self::PAID      => __(ucfirst(self::PAID)),
            self::SHIPPED   => __(ucfirst(self::SHIPPED)),
            self::COMPLETE  => __(ucfirst(self::COMPLETE)),
            self::REVIEWED  => __(ucfirst(self::REVIEWED)),
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

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('quantity', 'price', 'subtotal');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function billItems()
    {
        return $this->morphMany(BillItem::class, 'order');
    }

    public function display_info()
    {
        return [
            'avatar' => $this->user->avatar,
            'nickname' => $this->user->nickname,
            'mobile' => $this->user->mobile,
            // 'title' => $this->title,
            // 'detail' => $this->detail,
            'amount' => $this->amount,
            'created_at' => $this->created_at ? $this->created_at->toDateString() : null
        ];
    }

    public function info()
    {
        $info = parent::info();
        $info['address'] = $this->display_address();
        $info['store_name'] = $this->store->name ?? null;
        return $info;
    }

    public function detail()
    {
        $info = $this->info();
        $goods = [];
        foreach ($this->goods as $good) {
            $goods[] = $good->info();
        }
        $info['status_label'] = $this->statusLabel();
        $info['goods'] = $goods;
        $info['total_quantity'] = count($goods);
        $info['logistic_name'] = $this->logistic->name ?? null;
        if ($p = $this->logisticProgress) {
            $info['express'] = $p->data;
            $info['ship_status_label'] = $p->statusLabel();
        }
        $info['review_id'] = $this->review->id ?? null;

        return $info;
    }
}
