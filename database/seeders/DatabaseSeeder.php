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
            CategorySeeder::class,
            SettingSeeder::class,
            GoodsSeeder::class,
            StoreSeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            AddressSeeder::class,
            CartSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RedPacketSeeder::class,
            // LogisticSeeder::class,
        ]);
    }
}
