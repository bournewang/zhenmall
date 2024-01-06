<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class SetUserLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:user-level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set user level from order/goods';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$setting = Setting::first()){
            throw new \Exception("no settings found");
        }
        $catIds = [$setting->level_1_cat_id, $setting->level_2_cat_id];

        // fetch all orders
        $orders = Order::whereIn('status', [Order::PAID, Order::SHIPPED, Order::COMPLETE, Order::REVIEWED])->get();
        foreach ($orders as $order) {
            echo("parse order $order->id\n");
            // check goods
            $owner = User::find($order->user_id);
            foreach ($order->goods->whereIn("category_id", $catIds) as $goods) {
                // get config
                $to_level = null;
                echo("goods id: $goods->id, category id: $goods->category_id\n");
                if ($goods->category_id == $setting->level_1_cat_id) {
                    // update owner level
                    if ($owner->level < 1) {
                        $to_level = 1;
                        $rewards_days = $setting->level_1_rewards_days;
                    }
                }else{
                    if ($owner->level < 2) {
                        $to_level = 2;
                        $rewards_days = $setting->level_2_rewards_days;
                    }
                }

                if ($to_level) {
                    $expires_at = Carbon::parse($owner->created_at ?? '2023-12-31')->addDays($rewards_days)->toDateString();
                    echo("user level: $owner->level, need update to level $to_level, created_at: ".$owner->created_at.", rewards_expires_at: $expires_at\n");
                    $owner_data = [
                        'rewards_expires_at' => $expires_at,
                        'level' => $to_level
                    ];
                    $owner->update($owner_data);
                }

            }
        }
        return 0;
    }
}
