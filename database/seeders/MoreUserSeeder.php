<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
class MoreUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $i = 21;
        while ($i++ < 40 ){
            $ids = User::whereNotNull('store_id')->pluck('id')->all();
            $user = User::create([
                'store_id' => 1,
                'name' => 'test-'.$i,
                'email' => 'test-'.$i."@test.com",
                'password' => bcrypt('111111'),
                'referer_id' => $ids[rand(0, count($ids)-1)],
                'apply_status' => User::GRANT
            ]);
            echo "create user $user->name \n";
        }
    }
}
