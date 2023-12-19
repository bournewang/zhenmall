<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Health extends BaseModel
{
    use HasFactory;

    public $table = 'healths';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'expert_id',
        'detail',
        'suggestion',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'expert_id' => 'integer',
        'detail' => 'string',
        'suggestion' => 'string',
    ];

    protected $hidden = [
        'id',
    ];

    const PENDING = 'pending';
    const REPLIED = 'replied';
    const DENIED = 'denied';
    static public function statusOptions()
    {
        return [
            self::PENDING => __(ucfirst(self::PENDING)),
            self::REPLIED => __(ucfirst(self::REPLIED)),
            self::DENIED => __(ucfirst(self::DENIED)),
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
        return $this->belongsTo(User::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function info()
    {
        return array_merge(parent::info(), [
            'expert_img' => $this->expert->avatar ?? null,
            'expert_name' => $this->expert->name ?? null,
            'status_label' => $this->statusLabel()
            // 'customer_name' => $this->user->name ?? null,
        ]);
    }

    public function detail()
    {
        return array_merge($this->info(),[
            'imgs' => $this->getMediaData('main'),
        ]);
    }
}


