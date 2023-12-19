<?php

namespace App\Iot;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Device
{
    private $productKey;
    private $deviceName;
    private $deviceData;
    // deviceName can be a string or an array
    public function __construct($productKey, $deviceName)
    {
        $this->productKey = $productKey;
        $this->deviceName = $deviceName;
        $this->deviceData = [
            'IotInstanceId' => config('iot.iot_instance_id'),
            'ProductKey' => $this->productKey,
        ];
        if(is_array($deviceName)) {
            foreach ($deviceName as $i => $name) {
                $this->deviceData['DeviceName.'.($i+1)] = $name;
            }
        }else{
            $this->deviceData['DeviceName'] = $deviceName;
        }
    }
    
    public function detail()
    {    
        return IotClient::request('QueryDeviceDetail', $this->deviceData);
    }
    
    public function status()
    {
        return IotClient::request('GetDeviceStatus', $this->deviceData);
    }
    
    public function batchStatus()
    {
        return IotClient::request('BatchGetDeviceState', $this->deviceData);
    }
    
    public function shadow()
    {
        return IotClient::request('GetDeviceShadow', $this->deviceData);
    }

    public function property($data = [])
    {
        $url = 'QueryDevicePropertyStatus';
        if ($data) {
            $url = 'SetDeviceProperty';
            $this->deviceData['Items'] = json_encode($data);
        }
        return IotClient::request($url, $this->deviceData);
    }
}