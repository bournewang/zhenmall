<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\SalesOrder::create([
            'store_id' => 1,
            'user_id' => 3,
            'customer_id' => 5,
            'order_no' => '20220102334'.rand(1000,10000),
            'items' => [
                ["layout" => "items", "key" => '111111', "attributes" => ["goods_id" => 1, "quantity" => 2]],
                ["layout" => "items", "key" => '111111', "attributes" => ["goods_id" => 2, "quantity" => 3]]
            ],
            'total_price' => 22,
            'paid_price' => 20,
            'status' => 'shipped'
        ]);

    }
}
