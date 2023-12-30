<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends ApiBaseController
{
    /**
     * 获取用户信息
     *
     * @OA\Get(
     *  path="/api/user/info",
     *  tags={"User"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function info()
    {
        return $this->sendResponse($this->user->info());
    }

    /**
     * 修改用户类型
     *
     * @OA\Put(
     *  path="/api/user/type/{type}",
     *  tags={"User"},
     *  @OA\Parameter(name="type",  in="path",required=true,explode=true,@OA\Schema(type="string"),description="user type"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function type($type, Request $request)
    {
        if (!array_key_exists($type, User::typeOptions())) {
            return $this->sendError("invalid type $type");
        }
        $this->user->update(['type' => $type, 'status' => User::APPLYING]);
        return $this->sendResponse(null);
    }

    /**
     * 修改用户信息
     *
     * @OA\Post(
     *  path="/api/user/info",
     *  tags={"User"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="nickname",description="nickname",type="string"),
     *               @OA\Property(property="avarar",description="avarar",type="url"),
     *               @OA\Property(property="gender",description="1:male, 2:female",type="integer"),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function profile(Request $request)
    {
        $this->user->update($request->all());
        \Log::debug("user ".$this->user->id." update ");
        \Log::debug($request->all());
        return $this->sendResponse([]);
    }

    // code: 051prj0008KT6M1Eyb000YG9jS2prj0o
    // encryptedData: vgAcVXTqhTzoRImU/ekjxVVi/dKFx8XfjXbiZVDfxRA72wrmUhPUKOWp8FGqpi9YCR4TNyxfsGtG0/Zf/EQxeWxp3p+q2jXyMjNwQHsyijNfrIaqde43O4/M/fPcdjXGqy1+/VxMlMwHH1lBqlW0dtzXgWAtt18YqFLwrlk1+Q3ZtiaE+oacJhOzzVYy2L00dw9pSHl7ctCcK09KyCrEQw==
    // iv: qcWYc+YzH+CUjOjTyZrH0w==
    /**
     * 修改手机号
     *
     * @OA\Post(
     *  path="/api/user/mobile",
     *  tags={"User"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="code",description="login code",type="string"),
     *               @OA\Property(property="iv",description="iv from wx.login",type="string"),
     *               @OA\Property(property="encryptedData",description="encryptedData from wx.login",type="string"),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function mobile(Request $request)
    {
        $mpp = \EasyWeChat::miniProgram();
        $data = $mpp->phone_number->getUserPhoneNumber($request->input('code'));
        if ($mobile = ($data['phone_info']['purePhoneNumber'] ?? $data['phone_info']['phoneNumber'] ?? null)) {
            $this->user->update(['mobile' => $mobile]);
        }

        return $this->sendResponse($this->user->info());
    }

    /**
     * 获取用户二维码
     *
     * @OA\Get(
     *  path="/api/user/qrcode",
     *  tags={"User"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function qrcode()
    {
        $mpp = \EasyWeChat::miniProgram();
        $response = $mpp->app_code->getUnlimit("referer_id=".$this->user->id, ['page' => 'pages/index/index', 'check_path' => false]);
        \Log::debug($response);
        // 保存小程序码到文件
        $filename = null;
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->save(\Storage::disk('public')->path('user'), $this->user->id.".jpg");
            \Log::debug("save to $filename");
            $url = 'user/'.$filename;
            $this->user->update(['qrcode' => $url]);
            return $this->sendResponse(url($url));
        }
        return $this->sendError("获取二维码失败");
    }
}
