<?php

namespace App\Iot;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class IotClient
{
    static public function request($action, $query = [])
    {
        try {
            AlibabaCloud::accessKeyClient(config('iot.accessKeyId'), config('iot.accessSecret'))
                ->regionId(config('iot.region_id')) // replace regionId as you need
                ->asDefaultClient();
        } catch (ClientException $e) {
            \Log::error($e->getErrorMessage() . PHP_EOL);
        }
        
        try {
            $result = AlibabaCloud::rpc()
                ->product('Iot')
                ->scheme('https') // https | http
                ->method('POST')
                ->version(config('iot.version'))
                ->host(config('iot.host'))
                ->action($action)
                ->options(['query' => $query])
                ->request();
            \Log::debug("call $action ".json_encode($query));
            \Log::debug((string)$result->getBody());
            $data = $result->toArray();
            if ($data && $data['Success']){
                return $data;//['Data'] ?? [];
            }
            return [];
        } catch (ClientException $e) {
            \Log::error($e->getErrorMessage() . PHP_EOL);
        } catch (ServerException $e) {
            \Log::error($e->getErrorMessage() . PHP_EOL);
        }
    }

}