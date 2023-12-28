<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Setting;
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
        $users = User::where('rewards_expires_at', '>=', today())->get();
        $setting = Setting::first();
        foreach ($users as $user) {
            if ($user->level == 0) {
                $amount = rand($setting->level_0_rewards_min, $setting->level_0_rewards_max);
            }elseif ($user->level == 1) {
                $amount = rand($setting->level_1_rewards_min, $setting->level_1_rewards_max);
            }elseif ($user->level == 2) {
                $amount = rand($setting->level_2_rewards_min, $setting->level_2_rewards_max);
            }
            \Log::channel('money')->debug("create redpacket for user {$user->id} level: {$user->level}, amount: $amount");
            RedPacketHelper::create($user, $amount);
        }
        return 0;
    }
}
