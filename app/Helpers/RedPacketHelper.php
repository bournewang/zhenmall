<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\QuotaLog;
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
            $quota_balance = $owner->quota + $config['rewards_quota'];
            $owner->update([
                'rewards_expires_at' => $expires_at,
                'quota' => $quota_balance
            ]);
            QuotaLog::create([
                'store_id' => $order->store_id,
                'user_id' => $owner->id,
                'type' => 'deposit',
                'amount' => $config['rewards_quota'],
                'balance' => $quota_balance,
                'comment' => "下单",
                // 'open' => true
            ]);
            \Log::debug("update rewards_expires_at to $expires_at, add quota ".$config['rewards_quota']." to $quota_balance");

            \Log::debug("send red packets, category id: ".$goods->category_id.", level: ".$config['common_wealth_level']);
            $level = 1;
            if (!$referer = User::find($owner->referer_id)) {
                \Log::debug("no referer");
                continue;
            }
            do {
                $amount = rand($config['rewards_range']['min'], $config['rewards_range']['max']);
                $balance = $referer->balance + $amount;
                \Log::debug("sent redpacket for $referer->id amount: $amount, balance: $balance");
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
