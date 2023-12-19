<?php
namespace App\Helpers;
use Carbon\Carbon;
// use Image;
// use Storage;

class DeviceHelper
{
    // status: 0/1
    // device_name: device name, multi name can be join with ','
    // xxxx,yyyy,zzzz
    static public function statusChange($device_name, $status)
    {
        $url = "https://app.sy139.com/a_huayan/Huayan.asmx/huayan_fenli_device_changestatus";
        $key = "C3e5cn81GK5a!";
        $sign = md5($device_name . $key);
        $t = new Carbon('2020-01-01');
        $sign .= '+'.$t->diffInSeconds(Carbon::now());
        $data = [
            'gh' => \Auth::user()->id,
            'sn' => $device_name,
            'device_status' => $status,
            'en' => $sign
        ];
        $url .= '?' . http_build_query($data);
        \Log::debug("$url");
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url, ['timeout' => 10]);
        \Log::debug($res->getBody());
        return json_decode($res->getBody());
    }    
}