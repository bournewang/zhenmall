<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\BalanceLog;
use Carbon\Carbon;
use DB;

class UserHelper
{
    static public function qrcode($user)
    {
        $mpp = \EasyWeChat::miniProgram();
        $response = $mpp->app_code->getUnlimit("from=".$user->id, ['path' => '/pages/goods/list']);

        // 保存小程序码到文件
        $filename = null;
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $path  = "user/$user->id.jpg";
            $filename = $response->save(\Storage::disk('public')->path("user", "$user->id.jpg"));
            if (file_exists(\Storage::disk('public')->path($path))) {
                return $path;
            }
            // \Log::debug("save to $filename");
            // return $filename;
        }
        return null;
    }

    static public function team($user)
    {
        $str = cache1(tag_user($user), tag_user($user).".team", function()use($user){
            $team = DB::table('relation')->where('path', 'like', "%,$user->id,%");
            $yesterday_members = User::whereIn('id', $team->pluck('user_id'))->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()])->count();
            $team_members = $team->count();
            $direct_members = $user->juniors->count();
            $yesterday_income = $user->balanceLogs()->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()])->where('type', BalanceLog::DEPOSIT)->sum('amount');
            $today_income = $user->balanceLogs()->where("created_at", ">", Carbon::today())->where('type', BalanceLog::DEPOSIT)->sum('amount');
            $total_income = $user->balanceLogs()->where('type', BalanceLog::DEPOSIT)->sum('amount');
            return [
                ['label' => __("Team Members"),     "value" => $team_members],
                ["label" => __("Direct Members"),   'value' => $direct_members],
                ["label" => __("Yesterday Members"),'value' => $yesterday_members],
                ["label" => __("Yesterday Income"), 'value' => money($yesterday_income)],
                ["label" => __("Today Income"),     'value' => money($today_income)],
                ["label" => __("Total Income"),     'value' => money($total_income)],
            ];
        }, 3600);
        return json_decode($str);
    }

    static public function directRange()
    {
        $res = cache1("direct-members", "direct-members-range", function(){
            $res = DB::table('users as u1')
                ->join("users as u2", "u1.referer_id", "=", "u2.id")
                ->selectRaw("count(u1.id) as direct_members, u1.referer_id, u2.nickname, u2.mobile")
                ->whereNotNull('u1.referer_id')
                ->groupBy("u1.referer_id")
                ->orderByDesc("direct_members")
                ->limit(10)
                ->get();
                // ->toArray();
            $data = [];
            $i=1;
            foreach ($res as $item) {
                $data[]  =[
                    'index' => $i++,
                    'label' => $item->nickname ?? $item->mobile,
                    'value' => $item->direct_members
                ];
            }
            return $data;
            }, 3600 * 24);

        return json_decode($str);
    }

}
