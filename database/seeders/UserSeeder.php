<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Shop;
// use App\Models\Client;
// use Spatie\Permission\Models\Role;
// use Silvanite\Brandenburg\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->shop = Shop::first();
        if ($data = (config('seed')['user'] ?? null)) {
            foreach ($data as $item) {
                $pivot = $item['pivot'] ?? null;
                unset($item['pivot']);
                $item['password'] = bcrypt($item['password']);
                $user = User::create($item);
                // if ($store_id = ($pivot['store_id'] ?? null))
                    // $user->stores()->attach($store_id, $pivot);
                echo "create user $user->name \n";    
            }
        }
    }
}
