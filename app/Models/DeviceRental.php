<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class DeviceRental extends BaseModel
{
    use HasFactory;

    public $table = 'device_rentals';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'customer_id',
        'deposit_price',
        'paid_price',
        'validity_type',
        'validity_period',
        'validity_start',
        'validity_to',
        'status',
        'comment'
    ];


    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
//        'card_no' => 'string',
//        'total_price' => 'float',
        'deposit_price' => 'float',
        'paid_price' => 'float',
        'validity_type' => 'string',
        'validity_period' => 'integer',
        'validity_start' => 'date',
        'validity_to' => 'date',
        'status' => 'string',
        'comment' => 'string',
    ];

    protected $hidden = [
        'id',
    ];

    public static function beforesave(&$instance)
    {
        $instance->store_id = $instance->store_id ?? Auth::user()->store_id;
        $instance->user_id = $instance->user_id ?? Auth::user()->id;
        $instance->validity_start = $instance->validity_start ?? Carbon::today();
        $instance->status = $instance->status ?? self::VALID;
        if (!$instance->validity_to || // is empty
            $instance->getOriginal('validity_to') == $instance->validity_to // not changed
        ) {
            if (array_key_exists($instance->validity_type, self::periodOptions())) {
                $instance->validity_to = Carbon::parse($instance->validity_start)->add($instance->validity_period . " ".$instance->validity_type);
            }
        }
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

    const YEAR = 'year';
    const MONTH = 'month';
    const DAY = 'day';
//    const ACCOUNT = 'account';
    static public function periodOptions()
    {
        return [
            self::YEAR  => __(ucfirst(self::YEAR)) . __('Rental'),
            self::MONTH => __(ucfirst(self::MONTH)) . __('Rental'),
            self::DAY   => __(ucfirst(self::DAY)) . __('Rental'),
//            self::ACCOUNT => __(ucfirst(self::ACCOUNT)). __('Card')
        ];
    }
    public function periodLabel()
    {
        return self::periodOptions()[$this->validity_type] ?? "-";
    }

    const VALID = 'valid';
    const INVALID = 'invalid';
    const EXPIRED = 'expired';

    static public function statusOptions()
    {
        return [
            self::VALID => __(ucfirst(self::VALID)),
            self::INVALID => __(ucfirst(self::INVALID)),
            self::EXPIRED => __(ucfirst(self::EXPIRED))
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
        return $this->belongsTo(Clerk::class, 'user_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function billItems()
    {
        return $this->morphMany(BillItem::class, 'order');
    }
}


