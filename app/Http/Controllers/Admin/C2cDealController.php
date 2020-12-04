<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\C2cDeal;
use App\Models\Currency;

class C2cDealController extends Controller
{

    public function all()
    {
        return view('admin.c2c.all');
    }

    public function index()
    {

        // $currency = Currency::where('is_legal', 1)->orderBy('id', 'desc')->get();//获取法币
        //return view('admin.legal.deal', ['currency' => $currency]);
        return view('admin.c2c.deal');
    }

    public function list(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account_number = $request->get('account_number', '');
        $seller_number = $request->get('seller_number', '');
        $type = $request->get('type', '');
        $is_sure = $request->get('is_sure', '');
        // $currency_id = $request->get('currency_id', 0);
        $result = new C2cDeal();
        if (!empty($account_number)) {
            $result = $result->whereHas('user', function ($query) use ($account_number) {
                $query->where('account_number', 'like', '%' . $account_number . '%');
            });
        }
        if ($is_sure!=''){
            $result=$result->where('is_sure',$is_sure);
        }
        if (!empty($seller_number)) {

            $result = $result->whereHas('seller', function ($query) use ($seller_number) {
                $query->where('account_number', 'like', '%' . $seller_number . '%');
            });
        }

        if (!empty($type)) {
            $result = $result->whereHas('legalDealSend', function ($query) use ($type) {
                $query->where('type', $type);
            });

        }
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
        // if (!empty($currency_id)) {
        //     $result = $result->whereHas('legalDealSend', function ($query) use ($currency_id) {
        //         $query->where('currency_id', $currency_id);
        //     });
        // }

        $result = $result->orderBy('id', 'desc')->paginate($limit);
        $sum=$result->sum('number');
        return $this->layuiData($result,$sum);
    }

}