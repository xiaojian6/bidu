<?php

namespace App\Http\Controllers\Admin;

use http\Client\Curl\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Currency;
use App\Models\{Address, AccountLog, Users, UserCashInfo, UserReal, UsersWallet};

class DepositController extends Controller
{
    public function index()
    {
        return view("admin.deposit.depositList");
    }
    public function extract_index()
    {
        return view("admin.deposit.extractList");
    }
    public function coin_index()
    {
        $fixed = DB::table('deposit_return')->where("type",1)->select('id','currency','config')->first();
        $current = DB::table('deposit_return')->where("type",2)->select('id','currency','config')->first();
        $currency_type = Currency::all();
        $fixed = json_decode(json_encode($fixed),true);
        $current = json_decode(json_encode($current),true);
        $currency_type = json_decode(json_encode($currency_type),true);
        return view("admin.deposit.editcoin", [
            'currency_type' => $currency_type,
            'fixed'=>$fixed,
            "current" => $current,
        ]);
//        return view("admin.deposit.editcoin");
    }
    //获取所有存币记录
    public function deposit_list(Request $request){
        $limit = $request->get('limit', 20);
        $res = DB::table('deposit_lists')
            ->leftjoin("currency", "deposit_lists.currency", "=", "currency.id")
            ->leftJoin("percent","deposit_lists.per_id","=","percent.id")
            ->leftJoin("users","deposit_lists.user_id","=","users.id")
            ->select('deposit_lists.*','currency.name','percent.desc','percent.percent','users.account_number')
            ->orderBy('create_time','desc')
            ->where(function ($query) {
                $query->where('deposit_lists.type', '=', 1)
                    ->Where('deposit_lists.status', '=', 0);
            })
            ->orWhere(function ($query) {
                $query->where('deposit_lists.type', '=', 2)
                    ->Where('deposit_lists.status', '<', 2);
            })
            ->paginate($limit);
        $res_d = json_decode(json_encode($res->items()),true);
        foreach ($res_d as $ik => $iv)
        {
            $iv = json_decode(json_encode($iv),true);
            $res_d[$ik]['create_time'] = date("Y-m-d H:m:s",$iv['create_time']);
            $res_d[$ik]['limit_time'] = date("Y-m-d H:m:s",$iv['limit_time']);
            if($iv['type'] == 1)
            {
                $res_d[$ik]['type'] = "定期";
                if($iv['status'] == 0)
                {
                    $res_d[$ik]['status'] = "周期中";
                }
                elseif($iv['status'] == 1)
                {
                    $res_d[$ik]['status'] = "到期已返还";
                }
            }elseif($iv['type'] == 2)
            {
                $res_d[$ik]['type'] = "活期";
                if($iv['status'] == 0)
                {
                    $res_d[$ik]['status'] = "周期中";
                }
                elseif($iv['status'] == 1)
                {
                    $res_d[$ik]['status'] = "可转出";
                }
                elseif($iv['status'] == 2)
                {
                    $res_d[$ik]['status'] = "已转出";
                }
            }
        }
        return response()->json(['code' => 0, 'data' =>$res_d,'count' => $res->total()]);

    }
    //获取定期存币记录
    public function  regular_deposit_list(Request $request){

        $limit = $request->get('limit', 20);
        $where = array('deposit_lists.type'=>1,'deposit_lists.status'=>0);
        $res = DB::table('deposit_lists')
            ->leftjoin("currency", "deposit_lists.currency", "=", "currency.id","deposit_lists.ex_currency", "=", "currency.id")
            ->leftJoin("percent","deposit_lists.per_id","=","percent.id")
            ->leftJoin("users","deposit_lists.user_id","=","users.id")
            ->where($where)
            ->select('deposit_lists.*','currency.name','percent.desc','percent.percent','users.account_number')
            ->orderBy('create_time','desc')
            ->paginate($limit);
        if($res)
        {
            $data_lists = json_decode(json_encode($res->items()),true);
            foreach ($data_lists as $ik => $iv)
            {
                $iv = json_decode(json_encode($iv),true);
                $data_lists[$ik]['create_time'] = date("Y-m-d H:m:s",$iv['create_time']);
                $data_lists[$ik]['limit_time'] = date("Y-m-d H:m:s",$iv['limit_time']);
                $data_lists[$ik]['currency'] = $iv['name'];
                $data_lists[$ik]['ex_currency'] = $iv['name'];
                $data_lists[$ik]['desc'] = $iv['desc'];
                $data_lists[$ik]['percent'] = $iv['percent'];
                $data_lists[$ik]['user_id'] = $iv['account_number'];
                if($data_lists[$ik]['type']==1){
                    $data_lists[$ik]['type'] = "定期";
                    if($data_lists[$ik]['status']==0 )
                    {
                        $data_lists[$ik]['status'] = "周期中";
                    }elseif($data_lists[$ik]['status']==1)
                    {
                        $data_lists[$ik]['status'] = "已退回";
                    }
                }else if($data_lists[$ik]['type']==2){
                    $data_lists[$ik]['type'] = "活期";
                    if($data_lists[$ik]['status']==0)
                    {
                        $data_lists[$ik]['status'] = "周期中";
                    }elseif($data_lists[$ik]['status']==1)
                    {
                        $data_lists[$ik]['status'] = "可转出";
                    }
                };
            }
        }
        return response()->json(['code' => 0, 'data' =>$data_lists, 'count' => $res->total()]);
    }
    //获取活期存币记录
    public function current_deposit_list(Request $request){

        $limit = $request->get('limit', 20);
        $where1 = array('deposit_lists.type'=>2,'deposit_lists.status'=>0);
        $res = DB::table('deposit_lists')
            ->leftjoin("currency", "deposit_lists.currency", "=", "currency.id","deposit_lists.ex_currency", "=", "currency.id")
            ->leftJoin("percent","deposit_lists.per_id","=","percent.id")
            ->leftJoin("users","deposit_lists.user_id","=","users.id")
            ->where($where1)
            ->orWhere('deposit_lists.status',1)
            ->select('deposit_lists.*','currency.name','percent.desc','percent.percent','users.account_number')
            ->orderBy('create_time','desc')
            ->paginate($limit);
        if($res)
        {
            $data_lists = json_decode(json_encode($res->items()),true);
            foreach ($data_lists as $ik => $iv)
            {
                $iv = json_decode(json_encode($iv),true);
                $data_lists[$ik]['create_time'] = date("Y-m-d H:m:s",$iv['create_time']);
                $data_lists[$ik]['limit_time'] = date("Y-m-d H:m:s",$iv['limit_time']);
                $data_lists[$ik]['currency'] = $iv['name'];
                $data_lists[$ik]['ex_currency'] = $iv['name'];
                $data_lists[$ik]['desc'] = $iv['desc'];
                $data_lists[$ik]['percent'] = $iv['percent'];
                $data_lists[$ik]['user_id'] = $iv['account_number'];
                if($data_lists[$ik]['type']==1){
                    $data_lists[$ik]['type'] = "定期";
                    if($data_lists[$ik]['status']==0 )
                    {
                        $data_lists[$ik]['status'] = "周期中";
                    }elseif($data_lists[$ik]['status']==1)
                    {
                        $data_lists[$ik]['status'] = "已退回";
                    }
                }else if($data_lists[$ik]['type']==2){
                    $data_lists[$ik]['type'] = "活期";
                    if($data_lists[$ik]['status']==0)
                    {
                        $data_lists[$ik]['status'] = "周期中";
                    }elseif($data_lists[$ik]['status']==1)
                    {
                        $data_lists[$ik]['status'] = "可转出";
                    }
                    elseif($data_lists[$ik]['status']==2)
                    {
                        $data_lists[$ik]['status'] = "已转出";
                    }
                };
            }
        }
        return response()->json(['code' => 0, 'data' =>$data_lists, 'count' => $res->total()]);
    }

    //获取提币记录
    public function extract_list(Request $request){

        $limit = $request->get('limit', 20);
        $res = DB::table('deposit_lists')
            ->leftjoin("currency", "deposit_lists.ex_currency", "=", "currency.id")
            ->leftJoin("percent","deposit_lists.per_id","=","percent.id")
            ->leftJoin("users","deposit_lists.user_id","=","users.id")
            ->select('deposit_lists.*','currency.name','percent.desc','percent.percent','users.account_number')
            ->orderBy('create_time','desc')
            ->where(function ($query) {
                $query->where('deposit_lists.type', '=', 1)
                    ->Where('deposit_lists.status', '=', 1);
            })
            ->orWhere(function ($query) {
                $query->where('deposit_lists.type', '=', 2)
                    ->Where('deposit_lists.status', '=', 2);
            })
            ->paginate($limit);
        $res_d = json_decode(json_encode($res->items()),true);
        foreach ($res_d as $ik => $iv)
        {
            $iv = json_decode(json_encode($iv),true);
            $res_d[$ik]['create_time'] = date("Y-m-d H:m:s",$iv['create_time']);
            $res_d[$ik]['limit_time'] = date("Y-m-d H:m:s",$iv['limit_time']);
            if($iv['type'] == 1)
            {
                $res_d[$ik]['type'] = "定期";
                if($iv['status'] == 0)
                {
                    $res_d[$ik]['status'] = "周期中";
                }
                elseif($iv['status'] == 1)
                {
                    $res_d[$ik]['status'] = "到期已返还";
                }
            }elseif($iv['type'] == 2)
            {
                $res_d[$ik]['type'] = "活期";
                if($iv['status'] == 0)
                {
                    $res_d[$ik]['status'] = "周期中";
                }
                elseif($iv['status'] == 1)
                {
                    $res_d[$ik]['status'] = "可转出";
                }
                elseif($iv['status'] == 2)
                {
                    $res_d[$ik]['status'] = "已转出";
                }
            }
        }
        return response()->json(['code' => 0, 'data' =>$res_d,'count' => $res->total()]);
    }
    //获取币种配置
    public function coin_list(Request $request){

        $limit = $request->get('limit', 20);
        $res = DB::table('deposit_return')
            ->leftJoin('currency','deposit_return.currency','=','currency.id')
            ->select('deposit_return.*','currency.name')
            ->paginate($limit);
        if($res)
        {
            $data_lists = json_decode(json_encode($res->items()),true);
            foreach ($data_lists as $ik => $iv)
            {
                $iv = json_decode(json_encode($iv),true);
                $data_lists[$ik]['currency'] = $iv['name'];
                if($data_lists[$ik]['type']==1){
                    $data_lists[$ik]['type'] = "定期";
                }else{
                    $data_lists[$ik]['type'] = "活期";
                }
            }
        }
        return response()->json(['code' => 0, 'data' =>$data_lists, 'count' => $res->total()]);
    }

    //修改币种配置
    public function deposit_return_edit(Request $request){
        $s_data_fixed['currency'] = $request->get("fixed");
        DB::table("deposit_return")
            ->where("config","fixed")
            ->update($s_data_fixed);

        $s_data_currency['currency'] = $request->get("current");
        DB::table("deposit_return")
            ->where("config","current")
            ->update($s_data_currency);

        return $this->success("更新成功!");
    }
}
