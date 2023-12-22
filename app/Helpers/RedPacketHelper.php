<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\User;
use Carbon\Carbon;

class RedPacketHelper
{
    static public function sendRedPackets($order)
    {
        // categories need to send red packet
        $catIds = array_keys(config("referer.category"));
        $owner = User::find($order->user_id);
        foreach ($order->goods->whereIn("category_id", $catIds) as $goods) {
            $config = config("referer.category.".$goods->category_id);

            // update owner's rewards_expires_at and quota
            $expires_at = ($owner->rewards_expires_at ? Carbon::parse($owner->rewards_expires_at) : Carbon::today())
                ->addDays($config['rewards_days'])->toDateString();
            $owner->update([
                'rewards_expires_at' => $expires_at,
                'quota' => $owner->quota + $config['rewards_quota']
            ]);
            \Log::debug("update rewards_expires_at to $expires_at");

            \Log::debug("send packets, category id: ".$goods->category_id.", level: ".$config['common_wealth_level']);
            $level = 1;
            if (!$referer = User::find($order->user->referer_id)) {
                \Log::debug("no referer");
                continue;
            }
            do {
                $amount = rand($config['rewards_range']['min'], $config['rewards_range']['max']);
                $balance = $referer->balance + $amount;
                \Log::debug("set redpacket for $referer->id amount: $amount, balance: $balance");
                BalanceLog::create([
                    'store_id' => $order->store_id,
                    'user_id' => $referer->id,
                    'type' => 'deposit',
                    'amount' => $amount,
                    'balance' => $balance,
                    'comment' => "下单红包",
                    'open' => false
                ]);
                $referer->update(['balance' => $balance]);

                // send red packet for referer
                if (!$referer = User::find($referer->referer_id)) {
                    \Log::debug("no referer, break");
                    break;
                }
                // \Log::debug("level $level");
            } while ($level++ < $config['common_wealth_level']);
        }

    }
}
// 50 => [
//     // "category_id" => 50,
//     "level" => 5,
//     "type" => "red_packet",
//     "amount" => "random",
//     "range" => [
//         "min" => 10,
//         "max" => 20
//     ]
// ]
