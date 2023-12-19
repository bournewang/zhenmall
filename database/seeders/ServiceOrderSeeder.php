<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\ServiceOrder::create([
            'store_id' => 1,
            'device_id' => 1,
            'user_id' => 5,
            'amount' => 30,
            'order_no' => '2022010233445566',
            'title' => '养生按摩',
            'detail' => '30分钟养生按摩',
        ]);
    }
}
