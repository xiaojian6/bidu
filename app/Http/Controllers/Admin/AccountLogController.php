<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountLog;
use App\Models\Currency;
use App\Models\Users;
use Symfony\Component\HttpFoundation\Request;

class AccountLogController extends Controller
{

    public function index()
    {
        //获取type类型
        $type = array(
            AccountLog::ADMIN_LEGAL_BALANCE => '后台调节法币账户余额',
            AccountLog::ADMIN_LOCK_LEGAL_BALANCE => '后台调节法币账户锁定余额',
            AccountLog::ADMIN_CHANGE_BALANCE => '后台调节币币账户余额',
            AccountLog::ADMIN_LOCK_CHANGE_BALANCE => '后台调节币币账户锁定余额',
            AccountLog::ADMIN_LEVER_BALANCE => '后台调节杠杆账户余额',
            AccountLog::ADMIN_LOCK_LEVER_BALANCE => '后台调节杠杆账户锁定余额',
            AccountLog::WALLET_CURRENCY_OUT => '法币账户转出至交易账户',
            AccountLog::WALLET_CURRENCY_IN => '交易账户转入至法币账户',
            AccountLog::TRANSACTIONOUT_SUBMIT_REDUCE => '提交卖出，扣除',
            AccountLog::TRANSACTIONIN_REDUCE => '买入扣除',
            AccountLog::INVITATION_TO_RETURN => '邀请返佣金',
            AccountLog::ETH_TRANSFER_FEE => '打入ETH',
            AccountLog::BTC_TRANSFER_FEE => '打入BTC',
            AccountLog::TOKENS_WRAPPING => '代币归拢',
        );
        $currency_type = Currency::all();
        return view("admin.account.index", [
            'types' => $type,
            'currency_type' => $currency_type
        ]);
    }

    public function lists(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account = $request->get('account', '');
        $start_time = strtotime($request->get('start_time', 0));
        $end_time = strtotime($request->get('end_time', 0));
        $currency = $request->get('currency_type', 0);
        $type = $request->get('type', 0);

        $list = AccountLog::query();
        $list = $list->with(['user', 'walletLog']);
        if (!empty($currency)) {
            $list = $list->where('currency', $currency);
        }
        if (!empty($type)) {
            $list = $list->where('type', $type);
        }
        if (!empty($start_time)) {
            $list = $list->where('created_time', '>=', $start_time);
        }
        if (!empty($end_time)) {
            $list = $list->where('created_time', '<=', $end_time);
        }
        //根据关联模型的时间
        /*if(!empty($start_time)){
            $list = $list->whereHas('walletLog', function ($query) use ($start_time) {
                $query->where('create_time','>=',$start_time);
            });
        }
        if(!empty($end_time)){
            $list = $list->whereHas('walletLog', function ($query) use ($end_time) {
                $query->where('create_time','<=',$end_time);
            });
        }*/
        if (!empty($account)) {
            /*
            $list = $list->whereHas('user', function ($query) use ($account) {
                $query->where("phone", $account)->orWhere('email', $account);
            });
            */
            $user = Users::where("phone", 'like', '%' . $account . '%')->orwhere('email','like', '%' . $account . '%')->first();
            $list = $list->where(function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id);
                }
            });

        }

      /* if (!empty($account_number)) {
            $list = $list->whereHas('user', function($query) use ($account_number) {
            $query->where('account_number','like','%'.$account_number.'%'); 
             } );
        }*/

        //$list = $list->orderBy('id', 'desc')->toSql();
        //dd($list);
        $list = $list->orderBy('id', 'desc')->paginate($limit);
        //dd($list->items());
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function view(Request $request)
    {
        $id = $request->get('id', null);
        $results = new AccountLog();
        $results = $results->where('id', $id)->first();
        if (empty($results)) {
            return $this->error('无此记录');
        }
        return view('admin.account.viewDetail', ['results' => $results]);
    }

    public function recharge()
    {
        $currencies = Currency :: where('is_display', 1)
            ->get();
        return view('admin.account.recharge')->with('currencies', $currencies);
    }

    public function rechargeList(Request $request)
    {
        $limit = $request->input('limit', 10);
        $lists = AccountLog::where(function ($query) {
                $query->where('type', AccountLog::ETH_EXCHANGE)->where('user_id', '>', 0);
            })->whereHas('user', function ($query) use ($request) {
                $account_number = $request->input('account_number', '');
                $account_number != '' && $query->where('account_number', $account_number);
            })->where(function ($query) use ($request) {
                $currency = $request->input('currency', -1);
                $start_time = strtotime($request->input('start_time', null));
                $end_time = strtotime($request->input('end_time', null));
                $currency != -1 && $query->where('currency', $currency);
                $start_time && $query->where('created_time', '>=', $start_time);
                $end_time && $query->where('created_time', '<=', $end_time);
            })->orderBy('id', 'desc')->paginate($limit);
        $sum = $lists->sum('value');
        return $this->layuiData($lists,$sum);
    }

    public function indexprofits()
    {
        $scene_list = AccountLog::where("type", "=", AccountLog::PROFIT_LOSS_RELEASE)->orderBy("created_time", "desc")->get()->toArray();
//        var_dump($scene_list);die;
        return view('admin.profits.index')->with('scene_list', $scene_list);
    }

    public function listsprofits(Request $request)
    {
        $limit = $request->input('limit', 10);
        $prize_pool = AccountLog::whereHas('user', function ($query) use ($request) {
            $account_number = $request->input('account_number');
            if ($account_number) {
                $query->where('account_number', $account_number);
            }
        })->where(function ($query) use ($request) {
//            $scene = $request->input('scene', -1);
            $start_time = strtotime($request->input('start_time', null));
            $end_time = strtotime($request->input('end_time', null));
//            $scene != -1 && $query->where('scene', $scene);
            $start_time && $query->where('created_time', '>=', $start_time);
            $end_time && $query->where('created_time', '<=', $end_time);
        })->where("type", AccountLog::PROFIT_LOSS_RELEASE)->orderBy('id', 'desc')->paginate($limit);

        return $this->layuiData($prize_pool);
    }

    public function countprofits(Request $request)
    {
        $count_data = AccountLog::selectRaw('1 as user_count')
            ->selectRaw('sum(`value`) as value')
            ->whereHas('user', function ($query) use ($request) {
                $account_number = $request->input('account_number');
                if ($account_number) {
                    $query->where('account_number', $account_number)
                        ->orWhere('phone', $account_number)
                        ->orWhere('email', $account_number);
                }
            })->where(function ($query) use ($request) {
                //$scene = $request->input('scene', -1);
                $start_time = strtotime($request->input('start_time', null));
                $end_time = strtotime($request->input('end_time', null));
                //$scene != -1 && $query->where('scene', $scene);
                $start_time && $query->where('created_time', '>=', $start_time);
                $end_time && $query->where('created_time', '<=', $end_time);
            })->where("type", AccountLog::PROFIT_LOSS_RELEASE)->groupBy('user_id')->get();
        $user_count = $count_data->pluck('user_count')->sum();
        $reward_total = 0;
        $count_data->pluck('value')->each(function ($item, $key) use (&$reward_total) {
            $reward_total = bc_add($reward_total, $item);
        });
        return response()->json([
            'user_count' => $user_count,
            'reward_total' => $reward_total,
        ]);
    }
}
