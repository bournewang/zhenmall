<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipUsedItem extends BaseModel
{
    use HasFactory;

    public $table = 'membership_used_items';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'membership_card_id',
        'store_id',
        'user_id',
        'customer_id',
        'paid_price',
//        'validity_to',
    ];

    protected $casts = [
        'membership_card_id' => 'integer',
        'store_id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'paid_price' => 'float',
//        'validity_to' => 'date',
    ];

    protected $hidden = [
        'id',
    ];

    public function membershipCard()
    {
        return $this->belongsTo(MembershipCard::class);
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


