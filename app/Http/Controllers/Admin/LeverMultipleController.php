<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Models\LeverMultiple;
use App\Models\CurrencyMatch;
use App\Models\Setting;
use App\Models\UsersWallet;
use App\Models\Users;
use App\Models\Currency;
use Illuminate\Support\Facades\Input;


class LeverMultipleController extends Controller
{
    public function index()
    {
        return view('admin.levermultiple.index');
    }
    public function add()
    {
        return view('admin.levermultiple.add');
    }

    public function doadd(Request $request)
    {

        $aaaaaaa=new LeverMultiple();
        $aaaaaaa->value= Input::get('value', '');
        $aaaaaaa->type=Input::get('type', '');
//var_dump($aaaaaaa);die;
        try {
            $aaaaaaa->save();
        }catch (\Exception $ex){

        }
        return $this->success('添加成功');
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', 0);
        $name = $request->get('name', '');
        // $token = $request->get('token','');
        // $get_address = $request->get('get_address','');
        $sort = $request->get('sort', 0);
        $logo = $request->get('logo', '');
        $type = $request->get('type', '');
        $is_legal = $request->get('is_legal', '');
        $is_lever = $request->get('is_lever', '');
        $is_match = $request->get('is_match', '');
        $min_number = $request->get('min_number', 0);
        $rate = $request->get('rate', 0);
        $total_account = $request->get('total_account', 0);
        $key = $request->get('key', 0);
        $contract_address = $request->get('contract_address', 0);
        //自定义验证错误信息
        $messages = [
            'required' => ':attribute 为必填字段',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sort' => 'required',
            'type' => 'required',
            'is_legal' => 'required',
            'is_lever' => 'required',

            // 'logo'=>'required',
        ], $messages);

        //如果验证不通过
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $has = Currency::where('name', $name)->first();
        if (empty($id) && !empty($has)) {
            return $this->error($name . ' 已存在');
        }
        if (empty($id)) {
            $currency = new Currency();
            $currency->create_time = time();
        } else {
            $currency = Currency::find($id);
        }
        $currency->name = $name;
        // $acceptor->token = $token;
        // $acceptor->get_address = $get_address;
        $currency->sort = intval($sort);
        $currency->logo = $logo;
        $currency->is_legal = $is_legal;
        $currency->is_lever = $is_lever;
        $currency->is_match = $is_match;
        $currency->min_number = $min_number;
        $currency->rate = $rate;
        $currency->total_account = $total_account;
        $currency->key = $key;
        $currency->contract_address = $contract_address;
        $currency->type = $type;
        $currency->is_display = 1;
        DB::beginTransaction();
        try {
            $currency->save();//保存币种
            // if(empty($id)){// 如果是添加新币 //没添加一种交易币，就给用户添加一个交易币钱包
            //     $currency_id = Currency::where('name',$name)->first()->id;
            //     $users = Users::all();
            //     foreach ($users as $key => $value) {
            //         $userWallet = new UsersWallet();
            //         $userWallet->user_id = $value->id;
            //         $userWallet->currency = $currency_id;
            //         $userWallet->create_time = time();
            //         $userWallet->save();
            //     }
            // }
            DB::commit();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    public function lists(Request $request)
    {
//        $limit = $request->get('limit', 10);
//        $account_number = $request->get('account_number','');
        $result = new LeverMultiple();
        $count=$result::all()->count();
        $result = $result->orderBy("type","asc")->get()->toArray();
//        var_dump($result);die;
        foreach($result as $key=>$value)
        {
            if($value['type']==1)
            {
                $result[$key]['type']="倍数";
            }
            else
            {
                $result[$key]['type']="手数";
            }
        }

        return response()->json(['code' => 0, 'data' => $result, 'count' => $count]);
    }


    public function del()
    {
        $admin = LeverMultiple::find(Input::get('id'));
        if($admin == null) {
            abort(404);
        }
        $bool = $admin->delete();
        if($bool){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    public function edit(Request $request){

        $id = $request->get('id',0);
        if (empty($id)){
            return $this->error("参数错误");
        }

        $result = LeverMultiple::find($id);
        //
//        $res=UserCashInfo::where('user_id',$id)->first();

        return view('admin.levermultiple.edit',['result'=>$result]);
    }

    //编辑用户信息
    public function doedit(){
        $password = Input::get("value");
        $id = Input::get("id");
        if (empty($id)) return $this->error("参数错误");
        $user = LeverMultiple::find($id);
        $user->value=$password;
        if (empty($user)) return $this->error("数据未找到");
//        DB::beginTransaction();
        try {

            $aa=$user->save();
//            var_dump($aa);die;
            return $this->success('编辑成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }



    }

}
