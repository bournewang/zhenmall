<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\ServiceOrder;
// use App\Models\User;
use App\Helpers\StoreHelper;

class ServiceController extends ApiBaseController
{
    /**
     * Services in a store(for manager/clerk and referer) 服务订单列表
     *
     * @OA\Get(
     *  path="/api/services",
     *  tags={"Services"},
     *  @OA\Parameter(name="prev_month",    in="query",required=false,explode=true,@OA\Schema(type="integer"),description="previous month services data"),
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */       
    public function index(Request $request)
    {
        $month = date('Y-m');
        $start = date('Y-m-d', strtotime("first day of $month"));
        $end = date('Y-m-d', strtotime("last day of $month")) . ' 23:59:59';
        $res = \DB::table('service_orders')
            ->where('user_id', $this->user->id)
            ->whereBetween('created_at', [$start, $end])
            ->select("amount", "detail", \DB::raw("date(created_at) as date"))
            ->orderBy('id', 'desc')
            ->paginate($request->input('perpage', 20))
            ->toArray()
            ;
        return $this->sendResponse([
            'titles' => ['detail' => __('Detail'), 'amount' => __('Amount'), 'created_at' => __('Date')],
            'total' => $res['total'] ?? null,
            'pages' => $res['last_page'] ?? 1,
            'page' => $res['page'] ?? 1,
            'items' => $res['data']
        ]);
    }
}
