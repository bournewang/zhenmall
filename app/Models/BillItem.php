<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillItem extends BaseModel
{
    use HasFactory;

    public $table = 'bill_items';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'order_type',
        'order_id',
        'year',
        'month',
        'period',
        'role',
        'price',
        'share',
        'amount'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_type' => 'string',
        'order_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'period' => 'integer',
        'role' => 'string',
        'price' => 'integer',
        'share' => 'integer',
        'amount' => 'float',
    ];

    protected $hidden = [
        'id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    public function info()
    {
        return [
            'period' => implode('-',[$this->year, $this->month, __('Period Index', ['period' => $this->period])]),
            'role' => User::sharingRoleOptions()[$this->role] ?? '-',
            'price' => money($this->price),
            'share' => $this->share.'%',
            'amount' => money($this->amount),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
    static public function generate($order, $field="paid_at")
    {
        if (!$order->$field) return;
        $data = [
            'store_id' => $order->store_id,
            'order_type' => get_class($order),
            'order_id' => $order->id,
            'year' => $order->$field->format('Y'),
            'month' => $order->$field->format('m'),
            'period' => get_period($order->$field->format('d')),
            'price' => $order->paid_price ??( $order->total_price ?? $order->amount),
        ];
        if ($profit_sharing = $order->store->profit_sharing) {
            $sharing = $data;
            foreach ($profit_sharing as $item) {
                echo $item['role'].",".$item['sharing_ratio']."\n";
                if (array_key_exists($item['role'], User::sharingRoleOptions())){
                    if ($item['role'] == User::ROLE_VICE_MANAGER) {
                        $sharing['user_id'] = $order->store->vice_manager_id;
                    }elseif ($item['role'] == User::ROLE_REFERER) {
                        $sharing['user_id'] = $order->user->referer_id;
                    }
                    if (!$sharing['user_id']) {
//                        echo "user id is null, continue;\n";
                        continue;
                    }
                    $sharing['role'] = $item['role'];
                    $ratio = intval($item['sharing_ratio']);
                    $sharing['share'] = $ratio;
                    if ($ratio < 1 || $ratio > 100) {
                        throw new \Exception(__("Invalid Sharing Ratio", ['ratio' => $ratio]));
                    }
                    $sharing['amount'] = round($data['price'] * $ratio / 100, 2);
                    self::create($sharing);
                }
            }
        }
        return true;
    }
}
