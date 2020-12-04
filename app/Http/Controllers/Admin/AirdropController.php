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

class AirdropController extends Controller
{
    public function index()
    {
//        查询空投配置
        $res = DB::table('airdrop_config')->select('id','total','cur_currency','air_curency','stime','endTime')->first();
        $currency_type = Currency::all();
        $res = json_decode(json_encode($res),true);
        if($res)
        {
            $res['stime'] = date("Y-m-d H:m:s",$res['stime']);
            $res['endTime'] = date("Y-m-d H:m:s",$res['endTime']);
        }
        $locl = DB::table('lock_scale')->select('id','lock_scale')->first();
        return view("admin.airdrop.index", [
            'currency_type' => $currency_type,
            'air_date'=>$res,
            'lock_scale'=>json_decode(json_encode($locl),true)
        ]);
    }
    public function lock()
    {
        $locl = DB::table('lock_scale')->select('id','lock_scale')->first();
        return view("admin.airdrop.lock", [
            'lock_scale'=>json_decode(json_encode($locl),true)
        ]);
    }
    public function air_list(Request $request)
    {
        return view("admin.airdrop.airList");
    }
    public function addair(Request $request)
    {
        $currency_type = Currency::all();
        return view("admin.airdrop.addair", [
            'currency_type' => $currency_type
        ]);
    }
    //获取空投快照
    public function airdrop_list(Request $request){
        $limit = $request->get('limit', 20);
        $res = DB::table('airdrop_lists')->select('id','sort','account_number','total','createtime')->orderBy('id','asc')->paginate($limit);
        if($res)
        {
            $data_lists = json_decode(json_encode($res->items()),true);
            foreach ($data_lists as $ik => $iv)
            {
                $iv = json_decode(json_encode($iv),true);
                $data_lists[$ik]['createtime'] = date("Y-m-d H:m:s",$iv['createtime']);
            }
        }
        return response()->json(['code' => 0, 'data' =>$data_lists, 'count' => $res->total()]);
    }
    //空投配置列表
    public function airdrop_config_list(){
//        dd(222);
        $res = DB::table('airdrop_config')->select('total','cur_currency','air_curency','stime','endTime')->get();
        return response()->json(['code' => 0, 'data' => $res]);
    }
    //修改空投配置
    public function airdrop_edit(Request $request){
        $data_arr = array();
        $id = $request->get('id');
//        dd($id);
        $data_arr['cur_currency'] = $request->get('cur_currency');
        $data_arr['air_curency'] = $request->get('air_curency');
        $data_arr['stime'] =strtotime( $request->get('stime'));
        $data_arr['endTime'] = strtotime($request->get('endTime'));
        $data_arr['total'] = $request->get('total');
        if($data_arr)
        {
            $res = DB::table('airdrop_config')->where("id",$id)->update($data_arr);
            if($res)
            {
                return $this->success("更新成功!");
            }else
            {
                return $this->error("更新失败,请刷新后重试!");
            }
        }else
        {
            return $this->error("数据不能为空");
        }
//        $id = $request->get('id', 0);
//        if (empty($id)) {
//            return $this->error("参数错误");
//        }
//        dd();
//        $res = DB::table('airdrop_config')->select('config','cur_currency','air_curency','stime','endTime')->get();
//        return response()->json(['code' => 0, 'data' => $res->items()]);
    }
    //锁仓比例列表
    public function lock_scale_list(){
//        dd(222);
        $res = DB::table('lock_scale')->select('lock_scale')->get();
        return response()->json(['code' => 0, 'data' => $res]);
    }
    //修改锁仓比例
    public function lock_scale_edit(Request $request){
        $data_arr = array();
        $id = $request->get('id');
//        dd($id);
        $data_arr['lock_scale'] = $request->get('lock_scale');
        if($data_arr)
        {
            $res = DB::table('lock_scale')->where("id",$id)->update($data_arr);
            if($res)
            {
                return $this->success("更新成功!");
            }else
            {
                return $this->error("更新失败,请刷新后重试!");
            }
        }else
        {
            return $this->error("数据不能为空");
        }
    }
    public function currency_list(){
//        dd(222);
        $res = DB::table('currency')->select('id','name')->get();
        return response()->json(['code' => 0, 'data' => $res]);
    }
    //增加充值记录
    public function add_airdrop_record(Request $request)
    {
        $account = $request->get('account');
        //搜索用户ID
        $user_id = DB::table("users")
        ->where("account_number",$account)
        ->value('id');
        if($user_id)
        {
            $total = $request->get('total');
            $cur_currency = $request->get('cur_currency');

            $sdata['userid'] = array(
                "user_id" => $user_id,
                "value" => $total,
                "info" => "冲币到账",
                "type" => 200,
                "currency" => $cur_currency,
                "created_time" => time(),
            );
            $res = DB::table("account_log")
                ->insert($sdata);
            if($res)
            {
                return $this->success("充值成功!");
            }else
            {
                return $this->error("充值失败!");
            }
        }else
        {
            return $this->error("用户不存在!");
        }

    }
}
