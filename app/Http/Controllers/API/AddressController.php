<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends ApiBaseController
{
    /**
     * Get address list
     * @OA\Get(
     *  path="/api/address",
     *  tags={"Address"},     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */    
    public function index()
    {
        $data = [];
        foreach ($this->user->addresses as $addr){
            $data[] = $addr->detail();
        }
        return $this->sendResponse($data);
    }
    
    /**
     * Create address
     * @OA\Post(
     *  path="/api/address",
     *  tags={"Address"},     
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="province_id",   type="integer"),
     *               @OA\Property(property="city_id",       type="integer"),
     *               @OA\Property(property="district_id",   type="integer"),
     *               @OA\Property(property="street",        type="string"),
     *               @OA\Property(property="default",       type="integer"),
     *               @OA\Property(property="mobile",     type="string"),
     *               @OA\Property(property="contact",       type="string"),
     *           )
     *       )
     *   ),     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function create(Request $request)
    {
        \Log::debug($request->all());
        $input = $request->all();
        $input['user_id'] = $this->user->id;
        // $input['default'] = 0;
        if ($addr = Address::create($input)) {
            $key = $this->user->id . "current-address";
            \Cache::put($key, $addr->id);
            
            return $this->sendResponse($addr->id);
        }
    }
    
    /**
     * Update address
     * @OA\Put(
     *  path="/api/address/{id}",
     *  tags={"Address"},     
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="address id"),
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="province_id",   type="integer"),
     *               @OA\Property(property="city_id",       type="integer"),
     *               @OA\Property(property="district_id",   type="integer"),
     *               @OA\Property(property="street",        type="string"),
     *               @OA\Property(property="default",       type="integer"),
     *               @OA\Property(property="mobile",     type="string"),
     *               @OA\Property(property="contact",       type="string"),
     *           )
     *       )
     *   ),     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function update($id, Request $request) 
    {
        if ($addr = Address::find($id)) {    
            $addr->update($request->all());
        }

        return $this->sendResponse($addr->id);
    }
    /**
     * Address detail api
     *
     * @OA\Get(
     *  path="/api/address/{id}",
     *  tags={"Address"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="address id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function show($id, Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        // if ($addr = $this->user->addresses->find($id)) {
        if ($addr = Address::find($id)) {    
            return $this->sendResponse($addr->detail());
        }
        return $this->sendResponse([]);
    }
    
    /**
     * get default address api
     *
     * @OA\Get(
     *  path="/api/address/default",
     *  tags={"Address"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function default(Request $request)
    {
        \Log::debug($request->all());
        // $addr = Address::create($request->all());
        if ($addr = $this->user->addresses->where('default', 1)->first()) {
            return $this->sendResponse($addr->detail());
        }
        return $this->sendResponse([]);
    }
    
    public function select($id) 
    {
        $key = $this->user->id . "current-address";
        \Cache::put($key, $id);
        
        return $this->sendResponse($id);
    }
    
    public function current() 
    {
        $key = $this->user->id . "current-address";
        if ($id = \Cache::get($key)) {
            $addr = Address::find($id);
            return $this->sendResponse($addr->detail());
        }
        
        return $this->sendError("没有选择地址");
    }
    
    /**
     * Delete address api
     *
     * @OA\Delete(
     *  path="/api/address/{id}",
     *  tags={"Address"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="address id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function delete($id) 
    {
        if ($addr = Address::find($id)) {    
            $addr->delete();
        }

        return $this->sendResponse(null);
    }
}