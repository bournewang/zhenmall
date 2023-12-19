<?php

namespace App\Console\Commands;

use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bills {year_month_period?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate bills, for example: pph artisan generate:bill 2022-05-1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$year_month_period = $this->argument('year_month_period')) {
            $today = Carbon::today();
            $period = get_period($today->format('d'));
            if ($period == 1) {
                $year_month_period = $today->subMonth(1)->format('Y-m').'-3';
            }else{
                $year_month_period = $today->format('Y-m').'-'. ($period-1);
            }
        }
        $parts = explode('-', $year_month_period);

        \DB::table('bills')
            ->where('year',     intval($parts[0]))
            ->where('month',    intval($parts[1]))
            ->where('period',   intval($parts[2]))
            ->delete();

        echo "year month period: ".json_encode($parts);
        $res = \DB::table('bill_items')
            ->select('store_id', 'user_id', 'year', 'month', 'period', \DB::raw('sum(amount) as amount'))
            ->groupByRaw('store_id, user_id, year, month, period')
            ->where('year',     intval($parts[0]))
            ->where('month',    intval($parts[1]))
            ->where('period',   intval($parts[2]))
            ->get()
        ;
        foreach ($res as $item) {
            $data = get_object_vars($item);
            $data['status'] = Bill::OUTSTANDING;
            $bill = Bill::create($data);
            echo "create bill for store $bill->store_id user $bill->user_id \n";
        }

        return 0;
    }
}
