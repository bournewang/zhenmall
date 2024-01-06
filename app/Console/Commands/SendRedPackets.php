<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Setting;
use App\Models\RedPacket;
use App\Helpers\RedPacketHelper;

class SendRedPackets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:red-packets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send redpackets every day to all users';

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
        // clear unopen daily red packets sent in previous days
        RedPacket::where('open', 0)->where('type', RedPacket::TYPE_DAILY)->delete();

        $users = User::where('rewards_expires_at', '>=', today())->get();
        $min = 2 * 100; // 2
        $max = 18 * 100; // 10
        \Log::channel('money')->debug("red packet range, $min - $max");
        foreach ($users as $user) {
            $amount = round(rand($min, $max)/100, 2);
            \Log::channel('money')->debug("create redpacket for user {$user->id} amount: $amount");
            RedPacketHelper::create($user, $amount, RedPacket::TYPE_DAILY);
        }
        return 0;
    }
}
