<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use App\Models\Order;
use Log;

class WechatController extends ApiBaseController
{
    /**
     * Login api
     *
     * @OA\Post(
     *  path="/api/wxapp/login",
     *  tags={"Auth"},
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="code",description="code",type="string")
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function login(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if (!$code = $request->input('code')) {
            throw new ApiException("no code");
        }
        $mpp = \EasyWeChat::miniProgram();
        $data = $mpp->auth->session($code);
        \Log::debug($data);
        \Cache::put("wx.session.".$data['session_key'], json_encode($data), 60*5);
        if (isset($data['openid'])) {
            if ($user = User::where('openid', $data['openid'])->first()) {
                $user->refreshToken();
                \Auth::login($user);
                return $this->sendResponse($user->info());
            }
        }
        return $this->sendError('no user', [
            'session_key' => $data['session_key']
        ]);
    }

    /**
     * Register api
     *
     * @OA\Post(
     *  path="/api/wxapp/register",
     *  tags={"Auth"},
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="session_key",description="session key from login api response",type="string"),
     *               @OA\Property(property="iv",description="iv from wx.login",type="string"),
     *               @OA\Property(property="encryptedData",description="encryptedData from wx.login",type="string"),
     *               @OA\Property(property="store_id",description="store id from init",type="integer"),
     *               @OA\Property(property="referer_id",description="referer id from init",type="integer"),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function register(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if (!$session_key = $request->input('session_key')) {
            // throw new ApiException("no code");
        }
        $mpp = \EasyWeChat::miniProgram();
        $iv = $request->get('iv');
        $encryptedData = $request->get('encryptedData');
        $data = $mpp->encryptor->decryptData($session_key, $iv, $encryptedData);
        \Log::debug("decrypt data: ");
        \Log::debug($data);

        // if ($sess = \Cache::get("wx.session.".$session_key)) {
            // $session = json_decode($sess, 1);
        if (!$openid = ($data['openId'] ?? null)) {
            return $this->sendError("no openId in decrypt data");
        }

            $unionid = $data['unionid'] ?? null;
            $store_id = intval($request->input('store_id', null));
            $store_id = $store_id > 0 ? $store_id : null;
            if (!$user = User::where('openid', $openid)->first()) {
                \Log::debug("try to create user: ");
                $user = User::create([
                    'store_id'  => $store_id,
                    'referer_id' => $request->input('referer_id', null),
                    'openid'    => $openid,
                    'unionid'   => $unionid,
                    'email'     => $openid."@wechat.com",
                    'name'      => $data['nickName'] ?? null,
                    'nickname'  => $data['nickName'] ?? null,
                    'avatar'    => $data['avatarUrl'] ?? null,
                    'province'  => $data['province'] ?? null,
                    'city'      => $data['city'] ?? null,
                    'password'  => bcrypt($openid)
                ]);
                $user->refreshToken();
                \Auth::login($user);
                \Log::debug("user: $user->id");
                return $this->sendResponse($user->info());
            }
        // }
        // return $this->sendError("no openId in decrypt data");
    }

    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        $app = \EasyWeChat::payment();
        //  data:
        //  array (
        //   'appid' => 'wx561877352e872072',
        //   'bank_type' => 'OTHERS',
        //   'cash_fee' => '1',
        //   'fee_type' => 'CNY',
        //   'is_subscribe' => 'N',
        //   'mch_id' => '1484920352',
        //   'nonce_str' => '61d592e64368c',
        //   'openid' => 'oZO6h5ft4olVbJcLfU4OEkBqYdxc',
        //   'out_trade_no' => '891840',
        //   'result_code' => 'SUCCESS',
        //   'return_code' => 'SUCCESS',
        //   'sign' => '573C1A93A6AE80BA2B743A5BBA0D7639',
        //   'time_end' => '20220105204530',
        //   'total_fee' => '1',
        //   'trade_type' => 'JSAPI',
        //   'transaction_id' => '4200001310202201054219704874',
        // )
        $response = $app->handlePaidNotify(function ($data, $fail) {
            \Log::debug($data);
            if ($data['result_code'] == 'SUCCESS' &&
                $data['return_code'] == 'SUCCESS' &&
                ($order_no = $data['out_trade_no'])) {
                if ($order = Order::where('order_no', $order_no)->first()) {
                    $order->update(['status' => Order::PAID, 'paid_at' => Carbon::now()]);
                    // FIXME:
                    return true;
                }
            }
            // 或者错误消息
            $fail('Something going wrong.');
        });
        $response->send();
    }
}
