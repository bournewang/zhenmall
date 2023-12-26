<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RedPacket;
use App\Models\User;

class RedPacketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (User::all() as $user) {
            echo "create red packets for user $user->id\n";
            for ($i=0; $i<3; $i++) {
                RedPacket::create([
                    'store_id' => null,
                    'user_id' => $user->id,
                    'amount' => rand(10, 20),
                    'open' => rand(0,1)
                ]);
            }
        }
    }
}
