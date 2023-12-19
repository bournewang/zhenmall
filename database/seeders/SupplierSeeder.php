<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Supplier::create([
            'name' => '新新健康',
            'company_name' => '辽宁新新健康设备有限公司',
            'license_no'=> 'GHX339382726222',
            'account_no' => '2222333334444',
            'contact' => '李先生', 
            'mobile' => '12344445555'
        ]);
    }
}
