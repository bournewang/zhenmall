<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $store = \App\Models\Store::create([
            'name' => '沈河路店',
            'company_name' => '沈阳沈河健康养老有限公司',
            'account_no' => '11112222333',
            'license_no' => 'GMA33112234433',
            'contact' => '王先生',
            'mobile' => '13811112222',
            'vice_manager_id' => 2,
            'profit_sharing' => [
                [
                    "role" => "referer",
                    "sharing_ratio" => 15,
                ],
                [
                    "role" => "vice_manager",
                    "sharing_ratio" => 20,
                ],
            ]
            // 'commission' => 30
        ]);
    }
}
