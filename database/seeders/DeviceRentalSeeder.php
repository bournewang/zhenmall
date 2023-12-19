<?php

namespace Database\Seeders;

use App\Models\DeviceRental;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DeviceRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DeviceRental::create([
            'store_id' => 1,
            'user_id' => 4,
            'customer_id' => 5,
//            'card_no' => '223344',
            'deposit_price' => 3000,
            'paid_price' => 300,
            'validity_type' => DeviceRental::MONTH,
            'validity_period' => 3,
            'validity_to' => Carbon::today(),
            'validity_start' => Carbon::today()->subMonth(3),
//            'status' => DeviceRental::VALID,
            'comment' => null
        ]);
    }
}
