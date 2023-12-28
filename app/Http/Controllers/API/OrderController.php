<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use function EasyWeChat\Kernel\Support\generate_sign;
use App\Models\Order;
use App\Models\Address;
use App\Models\Review;
use App\Helpers\BalanceLogHelper;
use App\Helpers\OrderHelper;

class OrderController extends ApiBaseController
{    
    /**
     * Get order list 获取订单列表
     *
     * @OA\Get(
     *  path="/api/orders",
     *  tags={"Order"},
     *  @OA\Parameter(name="status",  in="query",required=false,explode=true,@OA\Schema(type="string"),description="order status"),
     *  @OA\Parameter(name="perpage", in="query",required=false,explode=true,@OA\Schema(type="integer"),description="order number per page"),
     *  @OA\Parameter(name="page",    in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page no"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */    
    public function index(Request $request)
    {
        $orders = $this->user->orders();
        if ($s = $request->input('status')) {
            $orders->where('status', $s);
        }
        $orders = $orders->orderBy('id', 'desc')->paginate(config('mall.per_page', 10));
        $data = [];
        foreach ($orders as $order) {
            $data[] = $order->detail();
        }
        
        return $this->sendResponse($data);
    }

    /**
     * Get order status summary 获取各状态订单数量
     *
     * @OA\Get(
     *  path="/api/orders/summary",
     *  tags={"Order"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */  
    public function summary(Request $request)
    {
        $orders = $this->user->orders();
        $res = $orders->select('status', \DB::raw('count(id) as total'))->groupBy('status')->pluck('total', 'status')->all();
        $all = array_sum($res);
        $res['all'] = $all;
        return $this->sendResponse($res);
    }
    
    /**
     * Place an order 提交订单
     *
     * @OA\Post(
     *   path="/api/orders",
     *   tags={"Order"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="address_id",
     *                   description="Address id",
     *                   type="integer"
     *               ),
     *               @OA\Property(
     *                   property="use_balance",
     *                   description="use balance",
     *                   type="boolean"
     *               )
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function create(Request $request)
    {
        if ($cart = $this->user->cart) {
            if (!$addr = Address::find($request->input('address_id'))){
                return $this->sendError("没有选择地址");
            }
            $order = $cart->submit($addr);
            if ($use_balance = $request->input('use_balance')) {
                // check balance
                if (($this->user->balance * 100) < ($cart->total_price * 100) ){
                    $this->sendError("余额不足: ".money($this->user->balance));
                }
                $log = BalanceLogHelper::consume($this->user, $order->amount, "下单抵扣");
                $this->user->update(['balance' => $log->balance]);
                OrderHelper::profitSplit($order);
                $order->update(['status' => Order::PAID]);
            }

            return $this->sendResponse($order->id, 'create order success');
        }
    }
    
    /**
     * Get order detail 获取订单详情
     *
     * @OA\Get(
     *  path="/api/orders/{id}",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function show($id)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__." $id");
        $order = $this->getOrder($id);
        return $this->sendResponse($order->detail());
    }
    
    /**
     * submit to wechat payment order 创建微信支付订单
     *
     * @OA\Put(
     *  path="/api/orders/{id}/place",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function place($id, Request $request) 
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__." order $id");
        $order = $this->getOrder($id);
        $app = \EasyWeChat::payment();
        $result = $app->order->unify([
            'body' => 'xxx-test-order',
            'out_trade_no' => $order->order_no,
            'total_fee' => $order->amount * 100,
            'trade_type' => 'JSAPI',
            'sign_type' => 'MD5',
            'openid' => $this->user->openid
        ]);               
        \Log::debug($result);
        if ($result['return_code']  === 'SUCCESS' && $result['result_code'] === 'SUCCESS')
        {
            // 二次验签
            $params = [
                'appId'     => config('wechat.payment.default.app_id'),
                'timeStamp' => time(),
                'nonceStr'  => $result['nonce_str'],
                'package'   => 'prepay_id=' . $result['prepay_id'],
                'signType'  => 'MD5',
            ];

            // config('wechat.payment.default.key')为商户的key
            $params['paySign'] = generate_sign($params, config('wechat.payment.default.key'));
            
            return $this->sendResponse($params);
        } else {
            return $this->sendError($result['err_code_des'] ?? '下单失败');
        }
    }
    
    /**
     * receive order 确认收货
     *
     * @OA\Put(
     *  path="/api/orders/{id}/receive",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function receive($id, Request $request) 
    {
        $order = $this->getOrder($id);
        $order->receive();
        
        return $this->sendResponse($order->detail());
    }
    
    
    /**
     * review order 评价
     *
     * @OA\Post(
     *  path="/api/orders/{id}/review",
     *  tags={"Order"},     
     *  @OA\Parameter(name="id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="order id"),
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="rating", type="integer", description="1-5"),
     *               @OA\Property(property="comment", type="string", description="comment"),
     *               @OA\Property(property="imgs[]", type="array", description="img urls", collectionFormat="multi", @OA\Items(type="string")),
     *           )
     *       )
     *   ),     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function review($id, Request $request) 
    {
        $order = $this->getOrder($id);
        if ($order->review) {
            return $this->sendError("order has already reviewed!");
        }
        $review = Review::create([
            'user_id' => $this->user->id,
            'store_id' => $this->user->store_id,
            'order_id' => $id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);
        $order->update(['status' => Order::REVIEWED]);
        foreach ($request->input('imgs') as $img){
            $path = public_path(str_replace(config('app.url'), '', $img));
            $review->addMedia($path)->toMediaCollection('photo');
        }
        
        return $this->sendResponse(null);
    }
    
    private function getOrder($id)
    {
        if (!$order = Order::find($id)) {
            throw new ApiException("order $id not found!");
        }
        if ($order->user_id != $this->user->id) {
            throw new ApiException("you can only get your own order!");
        }
        return $order;
    }
}