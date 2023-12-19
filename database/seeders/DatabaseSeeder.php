<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            GoodsSeeder::class,
            SupplierSeeder::class,
            StoreSeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            AddressSeeder::class,
            CartSeeder::class,
            DeviceSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            LogisticSeeder::class,
            ServiceOrderSeeder::class,
            SalesOrderSeeder::class,
            BillSeeder::class,
            MembershipCardSeeder::class,
            DeviceRentalSeeder::class,
            HealthSeeder::class
        ]);
    }
}
