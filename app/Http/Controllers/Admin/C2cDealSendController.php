<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\C2cDealSend;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class C2cDealSendController extends Controller
{
    public function index()
    {

        return view('admin.c2c.index');
    }

    public function list(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account_number = $request->get('account_number', '');
        //$seller_name = $request->get('seller_name', '');
        $type = $request->get('type', '');
        $is_done = $request->get('is_done', '');


       // $currency_id = $request->get('currency_id', 0);
        $result = new C2cDealSend();
        $start_time = $request->input('start_time', '');
        $end_time = $request->input('end_time', '');
        if (!empty($start_time)){
            $start_time=strtotime($start_time);
            $result=$result->where('create_time', '>=', $start_time);
        }
        if (!empty($end_time)){
            $end_time=strtotime($end_time);
            $result=$result->where('create_time', '<=', $end_time);
        }
        if (!empty($is_done)){
            $result=$result->where('is_done', $is_done);
        }
        if (!empty($account_number)) {

            $result = $result->whereHas('user', function ($query) use ($account_number) {
                $query->where('account_number', 'like', '%' . $account_number . '%');
            });
        }

        if (!empty($type)) {

            $result = $result->where('type', $type);
        }
        // if (!empty($currency_id)) {

        //     $result = $result->where('currency_id', $currency_id);
        // }
        $result = $result->orderBy('id', 'desc')->paginate($limit);
        $sum=$result->sum('total_number');
        return $this->layuiData($result,$sum);
    }

    //撤销
    public function sendBack(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            return $this->error('参数错误');
        }
        $send = C2cDealSend::find($id);
        if (empty($send)) {
            return $this->error('无此记录');
        }
        DB::beginTransaction();
        try {
            C2cDealSend::sendBack($id);
            DB::commit();
            return $this->success('发布撤回成功,此发布改变为已完成状态');
        } catch (\Exception $exception) {
            DB::rollback();
            return $this->error($exception->getMessage());
        }
    }
}
