<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Address;
class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if ($data = (config('seed')['address'] ?? null)) {
            foreach ($data as $item) {
                $c = Address::create($item);
                echo "create address $c->contact \n";
            }
        }
    }
}
