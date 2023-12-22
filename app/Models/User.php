<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use App\Helpers\UserHelper;
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use MediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'name',
        'openid',
        'unionid',
        'nickname',
        'avatar',
        'gender',
        'mobile',
        'province',
        'city',
        'county',
        'qrcode',
        'id_no',
        'id_status',
        'balance',
        'quota',
        // 'superiors',
        'referer_id',
        'type',
        'email',
        'password',
        'status',
        'rewards_expires_at',
        // 'level',
        // 'dd',
        // 'dds',  // number of dd
        // 'ppv',  // personal point value
        // 'gpv',  // all other sales' personal point value in your group;
        // 'tgpv', // gpv + ppv
        // 'pgpv', // tgpv (exclude qualified directors' tgpv)
        // 'agpv', // Accumulative Group Point Value  since your first month join
        // 'income_ratio',
        // 'retail_income',
        // 'level_bonus',
        // 'leader_bonus',
        // 'hlb', // has leader bonus
        // 'lbpv', // leader bonus point value
        // 'width_bonus',
        // 'depth_bonus',
        // 'total_income',
        // 'apply_status',
        // 'sharing',
        'api_token'
    ];

    public static $rules = [
        'name' => 'required|string',
        'gender' => 'integer',
        'mobile' => 'required',
        'rewards_expires_at' => 'date',
        'status' => 'string'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'level' => 'integer',
        'rewards_expires_at' => 'date',
        // 'sharing' => 'integer',
        'referer_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            // self::beforesave($instance);
            if (!$instance->email) {
                $instance->email = $instance->mobile.'@huayan.com';
                $instance->password = \bcrypt($instance->email);
            }
        });
    }
    const APPLYING = 'applying';
    const GRANT = 'grant';
    const REJECT = 'reject';
    public static function statusOptions()
    {
        return [
            self::APPLYING => __(ucfirst(self::APPLYING)),
            self::GRANT => __(ucfirst(self::GRANT)),
            self::REJECT => __(ucfirst(self::REJECT)),
        ];
    }

    const MALE = 1;
    const FEMALE = 2;
    public static function genderOptions()
    {
        return [
            self::MALE => __('Male'),
            self::FEMALE => __('Female')
        ];
    }
    public function genderLabel()
    {
        return self::genderOptions()[$this->gender] ?? '-';
    }

    const CUSTOMER = 'customer';
    const SALESMAN = 'salesman';
    const MANAGER = 'manager';
    const VICE_MANAGER = 'vice_manager';
    const CLERK = 'clerk';
    const EXPERT = 'expert';
    const STORE_KEEPER = 'store_keeper';
    const FINANCE = 'finance';

    public static function typeOptions()
    {
        $roles = [];
        foreach ([
            self::CUSTOMER,
            self::SALESMAN,
            self::MANAGER,
            self::VICE_MANAGER,
            self::CLERK,
            self::EXPERT,
            self::FINANCE,
            self::STORE_KEEPER,
        ] as $role){
            $roles[$role] = __(ucwords(str_replace('_', ' ', $role)));
        }
        return $roles;
    }

    public function typeLabel()
    {
        return __(ucfirst($this->type));
    }

    const ROLE_MANAGER = 'manager';
    const ROLE_VICE_MANAGER = 'vice_manager';
    const ROLE_REFERER = 'referer';
    public static function sharingRoleOptions()
    {
        return [
//            self::ROLE_MANAGER      => __(ucwords(str_replace('_', ' ',self::ROLE_MANAGER))),
            self::ROLE_VICE_MANAGER => __(ucwords(str_replace('_', ' ',self::ROLE_VICE_MANAGER))),
            self::ROLE_REFERER      => __(ucwords(str_replace('_', ' ',self::ROLE_REFERER))),
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class,'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'user_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Goods::class);
    }

    public function referer()
    {
        return $this->belongsTo(User::class, 'referer_id');
    }

    public function juniors()
    {
        return $this->hasMany(User::class, 'referer_id');
    }

    public function healths()
    {
        return $this->hasMany(Health::class);
    }

    public function balanceLogs()
    {
        return $this->hasMany(BalanceLog::class);
    }

    public function quotaLogs()
    {
        return $this->hasMany(QuotaLog::class);
    }

    public function isRoot()
    {
        return $this->id == 1;
    }

    public function members()
    {
        $members = $this->juniors;
        foreach ($this->juniors as $user) {
            $members = $members->concat($user->members());
        }
        return $members;
    }

    public function refreshToken()
    {
        $this->update(['api_token' => \Str::random(32)]);
    }

    public function info()
    {
        $attrs = [
            'id',
            'type',
            'name',
            'openid',
            'unionid',
            'nickname',
            'avatar',
            'gender',
            'mobile',
            'province',
            'city',
            'county',
            'balance',
            'quota',
            'api_token'
        ];
        foreach ($attrs as $attr){
            $data[$attr] = $this->$attr;
        }
        $data['qrcode'] = !$this->qrcode ? null : url(\Storage::url($this->qrcode));
        $data['store_name'] = $this->store->name ?? null;
        $data['type_label'] = $this->typeLabel();
        return $data;
    }

    public function detail()
    {
        return $this->info();
    }

    // personal bussiness value
    public function revenue($year, $index)
    {
        return $this->revenues()->where('year', $year)->where('index', $index)->first();
    }

    public function getCart()
    {
        if (!$cart = $this->cart) {
            $cart = Cart::create([
                'store_id' => $this->store_id,
                'user_id' => $this->id,
                'total_quantity' => 0,
                'total_price' => 0,
            ]);
        }
        return $cart;
    }
}
