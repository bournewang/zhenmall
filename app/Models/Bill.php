<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends BaseModel
{
    use HasFactory;

    public $table = 'bills';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'year',
        'month',
        'period',
        'amount',
        'status'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'period' => 'integer',
        'amount' => 'float',
        'status' => 'string'
    ];

    protected $hidden = [
        'id',
    ];

    const OUTSTANDING = 'outstanding';
    const CLOSED = 'closed';
    static public function statusOptions()
    {
        return [
            self::OUTSTANDING => __(ucfirst(self::OUTSTANDING)),
            self::CLOSED => __('Bill Closed')
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

    public function info()
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'period' => $this->period,
            'period_label' => implode('-',[$this->year, $this->month, __('Period Index', ['period' => $this->period])]),
            'amount' => money($this->amount),
            'status_label' => $this->statusLabel()
        ];
    }

    static public function generate($year, $month, $period)
    {
        $res = \DB::table('bill_items')
            ->select('store_id', 'user_id', 'year', 'month', 'period', \DB::raw('sum(amount) as amount'))
            ->groupByRaw('store_id, user_id, year, month, period')
            ->where('year', $year)
            ->where('month', $month)
            ->where('period', $period)
            ->get()
        ;
        self::where('year', $year)
            ->where('month', $month)
            ->where('period', $period)
            ->delete();
        foreach ($res as $item) {
            $data = get_object_vars($item);
            $data['status'] = Bill::OUTSTANDING;
//            var_dump($data);
            self::create($data);
//            echo "create bill for ". json_encode($data) . "\n";
        }
    }

}
