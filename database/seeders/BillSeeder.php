<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Order;
use App\Models\SalesOrder;
use App\Models\ServiceOrder;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (Order::all() as $order){
            BillItem::generate($order);
        }

        foreach (SalesOrder::all() as $order){
            BillItem::generate($order);
        }

        foreach (ServiceOrder::all() as $order) {
            BillItem::generate($order);
        }
        $res = \DB::table('bill_items')
                ->select('store_id', 'user_id', 'year', 'month', 'period', \DB::raw('sum(amount) as amount'))
                ->groupByRaw('store_id, user_id, year, month, period')
                ->get()
                ;
        foreach ($res as $item) {
            $data = get_object_vars($item);
            $data['status'] = Bill::OUTSTANDING;
            Bill::create($data);
        }
    }
}
