<?php
namespace App\Helpers;
use App\Models\Store;
use App\Models\Order;
use App\Models\User;
use App\Models\Revenue;
use DB;
class StoreHelper
{
    static public function relationIds($store_id, $relation, $attr=null, $val=null)
    {
        $key = implode('.', array_filter(['store', $store_id, __FUNCTION__, $relation, $attr, $val]));
        return cache1("store.$store_id", $key, function()use($store_id, $relation, $attr, $val){
            $builder = Store::find($store_id)->$relation();
            if ($attr && $val)
                $builder = $builder->wherePivot($attr, $val);
            return $builder->pluck('id')->all();
        });
    }

    static public function refreshSales($store, $start, $end)
    {
        foreach ($store->users as $user) {
            $user->initSales();
        }

        $res = Order::where('store_id', $store->id)
            ->whereBetween('paid_at', [$start, $end])
            ->select(\DB::raw("sum(orderAmount) as total"), 'user_id')
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->all();
        foreach ($res as $user_id => $total) {
            User::find($user_id)->update(['ppv' => $total]);
        }

        foreach ($store->refresh()->roots() as $root) {
            $root->tgpv();
        }

        foreach ($store->refresh()->roots() as $user) {
            $user->make_dds();
        }

        foreach ($store->refresh()->roots() as $user) {
            $user->make_leader_base();
        }

        foreach ($store->refresh()->users as $user) {
            $user->income();
        }
    }

    static public function calculateRevenue($store, $year, $index)
    {
        $start = date('Y-m-d', strtotime("first day of $year-$index"));
        $end = date('Y-m-d', strtotime("last day of $year-$index")) . ' 23:59:59';

        self::refreshSales($store, $start, $end);

        foreach ($store->refresh()->users as $user) {
            $data = [
                'ppv' => $user->ppv,
                'gpv' => $user->gpv,
                'tgpv' => $user->tgpv,
                'pgpv' => $user->pgpv,
                'retail_income' => $user->retail_income,
                'level_bonus'   => $user->level_bonus,
                'leader_bonus'  => $user->leader_bonus,
                'total_income'  => $user->total_income,
                'clearing_status' => 0
            ];
            if (!$revenue = $user->revenue($year, $index)) {
                $revenue = Revenue::create(array_merge([
                    'store_id' => $store->id,
                    'user_id' => $user->id,
                    'year' => $year,
                    'index' => $index,
                    'start'  => $start,
                    'end'  => $end,
                ], $data));
            }else{
                $revenue->update($data);
            }
        }
    }

    // 月度消费额, only for manager/clerk/referer
    static public function salesStats($user, $month, $perpage, $table = 'orders')
    {
        $start = date('Y-m-d', strtotime("first day of $month"));
        $end = date('Y-m-d', strtotime("last day of $month")) . ' 23:59:59';

        $builder = DB::table($table);
        if ($user->type == User::MANAGER) {
            $builder->where($table.'.store_id', $user->store_id);
        }else {
            $builder->where('users.referer_id', $user->id);
        }
        $res = $builder->whereIn($table.'.status', array_keys(Order::validStatus()))
            ->whereBetween($table.'.created_at', [$start, $end])
            ->select('users.avatar as img', 'users.nickname', 'users.mobile', DB::raw("sum(amount) as total_amount"))
            ->join('users', $table.'.user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->paginate($perpage)
            ->toArray()
            ;
        return [
            'titles'  => ['img' => __('Avatar'), 'nickname' => __('Nickname'), 'mobile' => __('Mobile'), 'amount' => __('Amount')],
            'total' => $res['total'] ?? null,
            'pages' => $res['last_page'] ?? 1,
            'page' => $res['page'] ?? 1,
            'items' => $res['data'] ?? [],
        ];
    }

    static private function stats($user, $start, $end, $table = 'orders', $price_field = 'amount')
    {
        $builder = DB::table($table);
        // if ($user->type == User::MANAGER) {
        $builder->where($table.'.store_id', $user->store_id);
        // }else {
        //     $builder->where('users.referer_id', $user->id);
        // }
        $res = $builder->whereIn($table.'.status', array_keys(Order::validStatus()))
            ->whereBetween($table.'.created_at', [$start, $end])
            ->select("referer_id", DB::raw("sum($price_field) as total_amount"))
            ->join('users', $table.'.user_id', '=', 'users.id')
            ->groupBy('referer_id')
            ->orderBy('total_amount', 'desc')
            ->pluck('total_amount', 'referer_id')
            ->all()
            // ->paginate(20)
            // ->toArray()
            ;
        return $res;
    }
    static public function salesStatsByReferer($user, $month, $perpage)
    {
        $start = date('Y-m-d', strtotime("first day of $month"));
        $end = date('Y-m-d', strtotime("last day of $month")) . ' 23:59:59';

        // \Log::debug(self::stats($user, $start, $end, $table));
        $arr1 = self::stats($user, $start, $end, 'orders');
        $arr2 = self::stats($user, $start, $end, 'service_orders');
        $arr3 = self::stats($user, $start, $end, 'sales_orders', 'total_price');

        $sums = array();
        foreach (array_keys($arr1 + $arr2 + $arr3) as $key) {
            $sums[$key] = @($arr1[$key] + $arr2[$key] + $arr3[$key]);
        }
        $data = [];
        $i=1;
        foreach ($sums as $referer_id => $total_amount) {
            if ($referer_id) {
                $referer = User::find($referer_id);
                $data[] = [
                    // 'index_no' => $i++,
                    'user_id' => $referer_id,
                    'img' => $referer->avatar,
                    'nickname' => $referer->nickname,
                    // 'mobile' => $referer->mobile,
                    'total_amount' => money($total_amount)
                ];
            }
        }
        return [
            'titles'  => ['img' => __('Avatar'), 'nickname' => __('Nickname'), 'total_amount' => __('Amount')],
            'total' => $res['total'] ?? null,
            'pages' => $res['last_page'] ?? 1,
            'page' => $res['page'] ?? 1,
            'items' => $data,
        ];
    }

    static private function statsItem($user, $start, $end, $table = 'orders', $price_field = 'amount')
    {
        $builder = DB::table($table);
        // if ($user->type == User::MANAGER) {
        //     $builder->where($table.'.store_id', $user->store_id);
        // }else {
        $builder->where('users.referer_id', $user->id);
        // }
        $res = $builder->whereIn($table.'.status', array_keys(Order::validStatus()))
            ->whereBetween($table.'.created_at', [$start, $end])
            ->select("user_id", DB::raw("sum($price_field) as total_amount"))
            ->join('users', $table.'.user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->pluck('total_amount', 'user_id')
            ->all()
            // ->paginate(20)
            // ->toArray()
            ;
        return $res;
    }
    static public function salesItemsByReferer($user, $month, $perpage)
    {
        $start = date('Y-m-d', strtotime("first day of $month"));
        $end = date('Y-m-d', strtotime("last day of $month")) . ' 23:59:59';

        // \Log::debug(self::stats($user, $start, $end, $table));
        $arr1 = self::statsItem($user, $start, $end, 'orders');
        $arr2 = self::statsItem($user, $start, $end, 'service_orders');
        $arr3 = self::statsItem($user, $start, $end, 'sales_orders', 'total_price');

        $sums = array();
        foreach (array_keys($arr1 + $arr2 + $arr3) as $key) {
            $sums[$key] = @($arr1[$key] + $arr2[$key] + $arr3[$key]);
        }
        $data = [];
        $i=1;
        foreach ($sums as $user_id => $total_amount) {
            if ($user_id) {
                $user = User::find($user_id);
                $data[] = [
                    // 'index_no' => $i++,
                    'user_id' => $user_id,
                    'img' => $user->avatar,
                    'nickname' => $user->nickname,
                    // 'mobile' => $referer->mobile,
                    'total_amount' => money($total_amount)
                ];
            }
        }
        return [
            'titles'  => ['img' => __('Avatar'), 'nickname' => __('Nickname'), 'total_amount' => __('Amount')],
            'total' => $res['total'] ?? null,
            'pages' => $res['last_page'] ?? 1,
            'page' => $res['page'] ?? 1,
            'items' => $data,
        ];
    }

}
