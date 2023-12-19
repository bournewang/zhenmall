<?php
namespace Database\Seeders;
use App\Models\Address;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Goods;
use App\Models\Cart;
use Carbon\Carbon;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $user = App\User::find(4);
        $store = Store::first();
        $ids = Goods::pluck('id')->all();
        $ids_total = count($ids);
        foreach (User::whereNotNull('store_id')->get() as $user) {
            if (!$cart = $user->cart) {
                $cart = Cart::create([
                    'store_id' => 1,
                    'user_id' => $user->id,
                ]);
            }
            $items = rand(1, 5);
            $c = 0;
            while ($c++ < $items){
                $i = rand(0, $ids_total - 1) ;
                $goods = Goods::find($ids[$i]);
                echo "add goods: $goods->id $goods->name \n";
                $cart->add($goods, 1 * rand(1,3));
            }
            $cart->update([
                'total_price' => $cart->goods->sum('pivot.subtotal'),
                'total_quantity' => $cart->goods->sum('pivot.quantity'),
            ]);

            // $date = "2021-07-".rand(28, 31). " ".rand(8,16).":".rand(0,59).":".rand(0,59);
            $date = Carbon::today()->subDay(rand(1, 30)-1)->subHour(rand(0,12))->subMinute(rand(0,59))->subSecond(rand(0, 59));

            echo "$user->id $user->name addresses: ". $user->addresses->count()."\n";
            if (!$address = $user->addresses->first()) {
                $address = (new Address(['contact' => 'test', 'mobile' => '10000000'.rand(100,999), 'province_id' => 1,'city_id'=>1, 'district_id'=>1, 'street' => '-']));
                \Log::debug("create address");
            }
            $order = $cart->refresh()->submit($address, $date);
            $order->update(['status' => 'shipped', 'paid_at' => $date]);

        }
    }
}
