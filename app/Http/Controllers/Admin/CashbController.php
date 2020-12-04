<?php

/**
 * 提币控制器
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\WithdrawAuditEvent;
use App\Models\{UsersWalletOut, UsersWallet, AccountLog, Currency, Setting, Users};
use phpDocumentor\Reflection\Types\Array_;

class CashbController extends Controller
{
    public function index()
    {
        return view('admin.cashb.index');
    }

    public function cashbList(Request $request)
    {
        $limit = $request->get('limit', 20);
        $account_number = $request->input('account_number', '');
        $start_time = $request->input('start_time', '');
        $end_time = $request->input('end_time', '');
        $userWalletOut = new UsersWalletOut();
        $userWalletOutList = $userWalletOut->where(function ($query) use ($account_number) {
            if (!empty($account_number)) {
                $user = Users::where('phone', $account_number)
                    ->orWhere('account_number', $account_number)
                    ->orWhere('email', $account_number)
                    ->first();
                if (!empty($user)) {
                    $query->where('user_id', $user->id);
                }
            }
        })->where(function ($query) use ($start_time, $end_time) {
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $query->where('create_time', '>=', $start_time);
            }
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $query->where('create_time', '<=', $end_time);
            }
        })->orderBy('id', 'desc')->paginate($limit);
        $sum = $userWalletOutList->sum('number');
        return $this->layuiData($userWalletOutList, $sum);
    }

    public function show(Request $request)
    {
        $id = $request->get('id', '');
        if (!$id) {
            return $this->error('参数小错误');
        }
        $walletout = UsersWalletOut::find($id);
        $in = AccountLog::where('type', AccountLog::ETH_EXCHANGE)
            ->where('user_id', $walletout->user_id)
            ->where('currency', $walletout->currency)
            ->sum('value');
        $out = UsersWalletOut::where('currency', $walletout->currency)
            ->where('user_id', $walletout->user_id)
            ->where('status', 2)
            ->sum('real_number');
        $use_chain_api = Setting::getValueByKey('use_chain_api', 0);
        return view('admin.cashb.edit', [
            'wallet_out' => $walletout,
            'out' => $out,
            'in' => $in,
            'use_chain_api' => $use_chain_api,
        ]);
    }

    /**
     * @param $remote_server
     * @param $post_string
     * @return bool|string
     */
    private function request_by_curl($url, $param) {
	        $postUrl = $url;
	        $curlPost = $param;
	        $ch = curl_init();//初始化curl
	        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
	        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	        $data = curl_exec($ch);//运行curl
	        curl_close($ch);

	        return $data;
    }

    /**
     * @param $tmpcode
     * @param $data
     */
    public function send_sms_notice($type,$p_code,$data)
    {
        $appid = 42028;
        $signature = "4367fae0c575894e7a083a7b9e3e11dc";
        $remote_server = "https://api.mysubmail.com/message/xsend";
        $to = $data['phone'];

        $project = $p_code;
        if($type == 1)
        {
            //发给用户
            $var = array(
                'type'=> $data['currency_type_notices']
            );
        }else
        {
            $var = array(
                'phone'=> $data['user_phone'],
                'type'=> $data['currency_type_notices'],
                'number'=>$data['number'],
            );
        }
        $vars = json_encode($var);

        $post_data = "appid=". $appid . "&to=" . $to . "&project=" . $project . "&vars=" . $vars . "&signature=" . $signature;

        $this->request_by_curl($remote_server,$post_data);

    }

    public function done(Request $request)
    {
        set_time_limit(0);
        $id = $request->get('id', 0);
        $method = $request->get('method', '');
        $txid =  $request->get('txid', '');
        $notes = $request->get('notes', '');
        $verificationcode = $request->input('verificationcode', '') ?? '';

        try {
            DB::beginTransaction();

            throw_if(empty($id), new \Exception('参数错误'));

            $balance_from = Setting::getValueByKey('withdraw_from_balance', 1); // 从哪个账户提币(1.法币,2.币币,3.杠杆)
            // $balance_type = [
            //     1 => ['legal', '法币'],
            //     2 => ['change', '币币'],
            //     3 => ['lever', '杠杆币'],
            // ];

            $wallet_out = UsersWalletOut::lockForUpdate()->findOrFail($id);

            $number = $wallet_out->number;
            /*
            edit by wyl
             */
            $user_phone_number = $wallet_out->account_number;
            //15243539173
            $admin_phone_number = "xxxxxxxxx";
            $currency_type_notice = $wallet_out->currency_name;
            /*
            edit by wyl
             */
            $user_notices_data = array(
                'phone' => $user_phone_number,
                'currency_type_notices' => "$currency_type_notice",
            );
            $admin_user_data = array(
                'phone' => $admin_phone_number,
                'currency_type_notices' => "$currency_type_notice",
                'number' => $number,
                'user_phone' => $user_phone_number,
            );

            $real_number = bc_mul($wallet_out->number, bc_sub(1, bc_div($wallet_out->rate, 100)));

            $user_id = $wallet_out->user_id;
            $currency = $wallet_out->currency;
            $currency_type = $wallet_out->currency_type;
            $user_wallet = UsersWallet::where('user_id', $user_id)
                ->where('currency', $currency)
                ->lockForUpdate()
                ->firstOrFail();

            $currency_model = Currency::find($currency);
            $contract_address = $currency_model->contract_address;
            $total_account = $currency_model->total_account;
            $key = $currency_model->origin_key;


            if ($method == 'done') {
                //确认提币
                if (empty($total_account) || empty($key)) {
                    throw new \Exception('请检查您的币种设置:(');
                }
                if (!in_array($currency_type, ['eth', 'erc20', 'usdt', 'btc'])) {
                    throw new \Exception('币种类型暂不支持:(');
                }
                if ($currency_type == 'erc20' && empty($contract_address)) {
                    throw new \Exception('币种设置缺少合约地址:(');
                }
                $change_result = change_wallet_balance($user_wallet, $balance_from, -$number, AccountLog::WALLETOUTDONE, '提币成功', true);
                if ($change_result !== true) {
                    throw new \Exception($change_result);
                }
                $use_chain_api = Setting::getValueByKey('use_chain_api', 0);
                if ($use_chain_api == 0) {
                    if ($txid == '') {
                        throw new Exception('当前提币没有使用接口,请填写交易哈希以便于用户查询');
                    }
                    $wallet_out->txid = $txid;
                } else {
                    throw_if(empty($verificationcode), new \Exception('请填写验证码'));
                }
                $wallet_out->use_chain_api = $use_chain_api;
                $wallet_out->status = 2; //提币成功状态
                //发送短信提醒
				// $this->send_sms_notice(1,"qM8mQ",$user_notices_data);
				// $this->send_sms_notice(2,"PVpDg1",$admin_user_data);

            } else {
                $change_result = change_wallet_balance($user_wallet, $balance_from, -$number, AccountLog::WALLETOUTBACK, '提币失败,锁定余额减少', true);
                if ($change_result !== true) {
                    throw new \Exception($change_result);
                }
                $change_result = change_wallet_balance($user_wallet, $balance_from, $number, AccountLog::WALLETOUTBACK, '提币失败,锁定余额撤回');
                if ($change_result !== true) {
                    throw new \Exception($change_result);
                }
                $wallet_out->status = 3; //提币失败状态
            }
            $wallet_out->notes = $notes;//反馈的信息
            $wallet_out->verificationcode = $verificationcode;
            $wallet_out->update_time = time();
            $wallet_out->save();
            event(new WithdrawAuditEvent($wallet_out));
            DB::commit();
            return $this->success('操作成功:)');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error('操作失败:' . 'File:' . $ex->getFile() . ',Line:' . $ex->getLine() . ',Message:' . $ex->getMessage());
        }
    }

    //导出用户列表至excel
    public function csv()
    {
        $data = USersWalletOut::all()->toArray();
        return Excel::create('提币记录', function ($excel) use ($data) {
            $excel->sheet('提币记录', function ($sheet) use ($data) {
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('ID');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('账户名');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('虚拟币');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('提币数量');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('手续费');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('实际提币');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('提币地址');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('反馈信息');
                });
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('状态');
                });
                $sheet->cell('J1', function ($cell) {
                    $cell->setValue('提币时间');
                });
                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        $i = $key + 2;
                        if ($value['status'] == 1) {
                            $value['status'] = '申请提币';
                        } else if ($value['status'] == 2) {
                            $value['status'] = '提币成功';
                        } else {
                            $value['status'] = '提币失败';
                        }
                        $sheet->cell('A' . $i, $value['id']);
                        $sheet->cell('B' . $i, $value['account_number']);
                        $sheet->cell('C' . $i, $value['currency_name']);
                        $sheet->cell('D' . $i, $value['number']);
                        $sheet->cell('E' . $i, $value['rate']);
                        $sheet->cell('F' . $i, $value['real_number']);
                        $sheet->cell('G' . $i, $value['address']);
                        $sheet->cell('H' . $i, $value['notes']);
                        $sheet->cell('I' . $i, $value['status']);
                        $sheet->cell('I' . $i, $value['create_time']);
                    }
                }
            });
        })->download('xlsx');
    }
}
