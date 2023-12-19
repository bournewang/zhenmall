<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (config('seed.devices') as $key => $names) {
            foreach ($names as $name) {
                \App\Models\Device::create([
                    'store_id' => \App\Models\Store::first()->id,
                    'product_key' => $key,
                    'device_name' => $name,
                    'status' => 1
                ]);
            }
        }
    }
}
