<?php
// use Cache;
use Carbon\Carbon;
function cache1($tags, $key, $callback, $expires = null)
{
    // \Log::debug(__FUNCTION__);
    $cache = Cache::store('redis')->tags($tags);
    if ($cache->has($key)){
        \Log::debug(" ------- from   cache $key");
        return $cache->get($key);
    }else{
        $value = call_user_func($callback);
        $str = json_encode($value);
        \Log::debug(" +++++++ refresh cache $key $str");
        $cache->put($key, $str, $expires ?? 3600 * 24);
        return $value;
    }
}

function tag_user($user) {
    if (is_int($user) || is_string($user)) {
        return "user.$user";
    }else{
        return "user.$user->id";
    }
}

function flush_tag($tag){
    Cache::store('redis')->tags($tag)->flush();
}

function hash2array($hash)
{
    $arr = [];
    foreach($hash as $val => $label){
        $arr[] = ['value' => $val, 'display' => $label];
    }
    return $arr;
}

function money($val)
{
    return !$val ? null : sprintf(__('RMB')."%.2f", $val);
}

function storage_url($img)
{
    return $img ? url(\Storage::url($img)) : null;
}

function benchmark_start()
{
    if (function_exists('xhprof_enable')) {
        xhprof_enable();
    }
}
function benchmark_end($request = null)
{
    if (function_exists('xhprof_disable')) {
        $xhprof_data = xhprof_disable();
        $XHPROF_ROOT = config('xhprof.dir');
        include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
        include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo"); // 查看地址
        \Log::debug("url: ".$request->fullUrl());
        \Log::debug("http://xhprof.local/index.php?run=$run_id&source=xhprof_foo");
    }
}

function new_order_no()
{
    return Carbon::now()->format('YmdGis').rand(100000,999999);
}

function get_period($day)
{
    if ($day > 20) {
        $period = 3;
    }elseif($day > 10) {
        $period = 2;
    }else{
        $period = 1;
    }
    return $period;
}
