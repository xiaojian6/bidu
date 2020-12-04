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

class LockupController extends Controller
{
    public function index()
    {
//        查询空投配置
        $res = DB::table('rechange_config')->select('id','t_precent','t_rate','currencys')->first();
        $currency_type = Currency::all();
        $res = json_decode(json_encode($res),true);
        return view("admin.lockup.index", [
            'currency_type' => $currency_type,
            'air_date'=>$res,
        ]);
    }

    //修改空投配置
    public function lockup_edit(Request $request){
        $data_arr = array();
        $id = $request->get('id');
//        dd($id);
        $data_arr['t_precent'] = $request->get('t_precent');
        $data_arr['t_rate'] = $request->get('t_rate');
        if($data_arr)
        {
//            dump($data_arr);die;
            $res = DB::table('rechange_config')->where("id",$id)->update($data_arr);
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

}
