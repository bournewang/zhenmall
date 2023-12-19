<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Store extends BaseModel
{
    use SoftDeletes;
    use StatusTrait;
    use AddressTrait;

    public $table = 'stores';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        // 'id',
        'name',
        'company_name',
        'license_no',
        'account_no',
        'contact',
        'mobile',
        'vice_contact',
        'vice_mobile',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'status',
        'manager_id',
        'vice_manager_id',
        'salesman_id',
        'profit_sharing'
        // 'commission'
    ];

    protected $casts = [
        'name' => 'string',
        'company_name' => 'string',
        'license_no' => 'string',
        'account_no' => 'string',
        'contact' => 'string',
        'mobile' => 'string',
        'vice_contact' => 'string',
        'vice_mobile' => 'string',
        'profit_sharing' => 'json'
        // 'license_img' => 'string',
        // 'commission' => 'integer'
    ];

    public static $rules = [
        'name' => 'required|string|unique:stores',
//        'company_name' => 'required|string',
        'contact' => 'required|string',
        'mobile' => 'required|string|max:11|min:11',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'shopId',
    ];

     protected static function beforesave(&$instance)
     {
         if (!$instance->manager_id && $instance->mobile) {
             if ($user = User::where('mobile', $instance->mobile)->first()){
                 $instance->manager_id = $user->id;
             }
         }
         if (!$instance->vice_manager_id && $instance->vice_mobile) {
             if ($user = User::where('mobile', $instance->vice_mobile)->first()){
                 $instance->vice_manager_id = $user->id;
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

    public function flush()
    {
        flush_tag("store.$this->id");
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);//->withPivot('superior_id', 'level', 'sharing');
    }

    public function clerks()
    {
        return $this->users()->where('type', User::CLERK);
    }

    public function customers()
    {
        return $this->users()->where('type', User::CUSTOMER)->get();
    }

    public function roots()
    {
        return $this->users()->whereNull('referer_id')->get();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function manager(){return $this->belongsTo(User::class);}
    public function viceManager(){return $this->belongsTo(User::class);}
    public function salesman(){return $this->belongsTo(User::class);}
    public function devices(){return $this->hasMany(Device::class);}

    public function detail()
    {
        return array_merge($this->info(),[
            'contract' => $this->getMediaData('contract'),
            'license' => $this->getMediaData('license'),
            'photo' => $this->getMediaData('photo'),
            'id_card' => $this->getMediaData('id_card'),
            'address' => $this->display_address(),
            'status_label' => $this->statusLabel()
        ]);
    }
}
