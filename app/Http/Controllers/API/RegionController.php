<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\City;

class RegionController extends ApiBaseController
{
    /**
     * Provinces list api
     *
     * @OA\Get(
     *  path="/api/provinces",
     *  tags={"Region"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */  
    public function provinces(Request $request)
    {
        return (hash2array(Province::all()->pluck('name','id')->all()));
    }
    
    /**
     * Cities list api
     *
     * @OA\Get(
     *  path="/api/provinces/{province_id}/cities",
     *  tags={"Region"},
     *  @OA\Parameter(name="province_id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="province_id"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */  
    public function cities($province_id, Request $request)
    {
        if ($province = Province::find($province_id)) {
            return (hash2array($province->cities->pluck('name','id')->all()));
        }
    }
    
    /**
     * Districts list api
     *
     * @OA\Get(
     *  path="/api/cities/{city_id}/districts",
     *  tags={"Region"},
     *  @OA\Parameter(name="city_id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="city_id"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */  
    public function districts($city_id, Request $request)
    {
        if ($city = City::find($city_id)) {
            return (hash2array($city->districts->pluck('name','id')->all()));
        }
    }
}
