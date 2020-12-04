<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AccountLog;
use App\Models\WalletLog;
use App\Models\Currency;

class PoundageController extends Controller
{
    protected static $type = [
        //C2C手续费
        '0' => [
            AccountLog::C2C_TRADE_FEE,
            AccountLog::C2C_CANCEL_TRADE_FEE ,
        ],
        //法币交易手续费
        '1' => [
            AccountLog::LEGAL_TRADE_FREE, //撮合交易[卖出]手续费
            AccountLog::LEGAL_CANCEL_TRADE_FREE, //撮合交易<买入>成功手续费
        ],
        //撮合交易手续费
        '2' => [
            AccountLog::MATCH_TRANSACTION_SELL_FEE, //撮合交易[卖出]手续费
            AccountLog::MATCH_TRANSACTION_BUY_FEE, //撮合交易<买入>成功手续费
            AccountLog::MATCH_TRANSACTION_CANCEL_SELL_FEE, //撮合交易取消[卖出]撤回手续费
            AccountLog::MATCH_TRANSACTION_CANCEL_BUY_FEE, //撮合交易取消<买入>撤回手续费
        ],
        //杠杆交易手续费
        '3' => [
            AccountLog::LEVER_TRANSACTION_FEE,
            AccountLog::LEVER_TRANSACTION_FEE_CANCEL,
        ],
    ];

    protected static $typeName = [
        'C2C交易',
        '法币交易',
        '撮合交易',
        '杠杆交易',
    ];

    public function index()
    {
        $currencies = Currency::all();
        return view('admin.poundage.index')->with('currencies', $currencies);
    }

    public function lists(Request $request)
    {
        $limit = $request->input('limit', 10);
        $account_number = $request->input('account_number', '');
        $account_log = AccountLog::whereHas('user', function ($query) use ($account_number) {
                if (!empty($account_number)){
                    $query->where('phone', $account_number)->orWhere('account_number', $account_number)->orWhere('email', $account_number);
                }
            })->where(function ($query) use ($request) {
                $all_type = $this->mergeAllChild(self::$type);
                $type = $request->input('type', -1);
                $currency = $request->input('currency', -1);
                $start_time = $request->input('start_time', '');
                $end_time = $request->input('end_time', '');
                
                if (!empty($start_time)){
                    $start_time=strtotime($start_time);
                    $query->where('created_time', '>=', $start_time);
                }
                if (!empty($end_time)){
                    $end_time=strtotime($end_time);
                    $query->where('created_time', '<=', $end_time);
                }
                if ($currency != -1) {
                    $query->where('currency', $currency);
                }
                $type != -1 ? $query->whereIn('type', self::$type[$type]): $query->whereIn('type', $all_type);
            });
        
        $paginate = $account_log->orderBy('id','desc')->paginate($limit);
        $sum = $account_log->sum('value');
        return $this->layuiData($paginate, $sum);
    }

    public function sum()
    {
        $all_type = $this->mergeAllChild(self::$type);
        $sum = AccountLog::whereIn('type',$all_type)->sum('value');
        return $this->success($sum);
    }

    public function mergeAllChild($array)
    {
        $data = [];
        foreach ($array as $key => $value) {
            $data = array_merge($data, $value);
        }
        return $data;
    }
}
