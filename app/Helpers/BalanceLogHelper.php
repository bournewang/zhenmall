<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Models\BalanceLog;

class BalanceLogHelper
{
    static public function consume($user, $amount, $comment = null)
    {
        $log = BalanceLog::create([
            'store_id' => $user->store_id,
            'user_id' => $user->id,
            'type' => 'consume',
            'amount' => $amount * -1,
            'balance' => $user->balance - $amount,
            'comment' => $comment,
            'open' => true
        ]);
        $user->update(['balance' => $log->balance]);
        return $log;
    }
}
