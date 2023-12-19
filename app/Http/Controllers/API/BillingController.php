<?php

namespace App\Http\Controllers\API;


use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;

class BillingController extends ApiBaseController
{
    /**
     * Billing list api
     *
     * @OA\Get(
     *  path="/api/billing",
     *  tags={"Billing"},
     *  @OA\Parameter(name="year",      in="query",required=false,explode=true,@OA\Schema(type="integer"),description="year,example: 2022"),
     *  @OA\Parameter(name="month",     in="query",required=false,explode=true,@OA\Schema(type="integer"),description="month, 1-12"),
     *  @OA\Parameter(name="perpage",   in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",      in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function index(Request $request)
    {
        $builder = Bill::where('user_id', $this->user->id);
        if ($year = $request->input('year')) {
            $builder->where('year', $year);
        }
        if ($month = $request->input('month')) {
            $builder->where('month', $month);
        }
        $data = $this->paginateInfo($builder, $request);
        $data['titles'] = ['period_label' => __('Settlement Period'), 'amount' => __('Amount'), 'status_label' => __('Status')];
        return $this->sendResponse($data);
    }

    /**
     * Billing items api
     *
     * @OA\Get(
     *  path="/api/billing/items",
     *  tags={"Billing"},
     *  @OA\Parameter(name="year",      in="query",required=false,explode=true,@OA\Schema(type="integer"),description="year,example: 2022"),
     *  @OA\Parameter(name="month",     in="query",required=false,explode=true,@OA\Schema(type="integer"),description="month, 1-12"),
     *  @OA\Parameter(name="period",    in="query",required=false,explode=true,@OA\Schema(type="integer"),description="period, 1-3"),
     *  @OA\Parameter(name="perpage",   in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",      in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function items(Request $request)
    {
        $builder = BillItem::where('user_id', $this->user->id);
        if ($year = $request->input('year')) {
            $builder->where('year', $year);
        }
        if ($month = $request->input('month')) {
            $builder->where('month', $month);
        }
        if ($period = $request->input('period')) {
            $builder->where('period', $period);
        }
        $data = $this->paginateInfo($builder, $request);
        $data['titles'] = [
            'period' => __('Settlement Period'),
            'price' => __('Consume Price'),
            'role' => __('Role'),
            'share' => __('Share'),
            'amount' => __('Amount'),
            'created_at' => __('Date')
        ];
        return $this->sendResponse($data);
    }
}
