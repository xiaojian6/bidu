<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{AccountLog, Currency, CurrencyMatch, Transaction, TransactionComplete, TransactionIn, TransactionOut, Users, LeverTransaction, TransactionOrdercopy, UserReal};

class TransactionController extends Controller
{

    public function index()
    {
        $currency = Currency::all();
        return view("admin.transaction.index", ['currency' => $currency]);
    }

    public function lists()
    {
        $limit = Input::get('limit', 10);
        $account_number = Input::get('account_number', ''); //用户交易账号
        $type = Input::get('type', '');
        $currency = Input::get('currency', '');
        $status = Input::get('status', '');
        $result = new Transaction();
        if (!empty($account_number)) {

            $users = Users::where('account_number', 'like', '%' . $account_number . '%')->get()->pluck('id');
            $result = $result->where(function ($query) use ($users) {
                $query->whereIn('from_user_id', $users);
            });
            // ->orWhere(function($query) use ($users){
            //     $query->whereIn('to_user_id',$users);
            // });
        }

        if (!empty($type)) {
            $result = $result->where('type', '=', $type);
        }
        if (!empty($currency)) {
            $result = $result->where('currency', $currency);
        }
        if (!empty($status)) {
            $result = $result->where('status', $status);
        }


        $list = $result->orderBy('id', 'desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function completeIndex()
    {
        $legal_currencies = Currency::where('is_legal', 1)->get();
        $currencies = Currency::get();
        return view("admin.transaction.complete", [
            'legal_currencies' => $legal_currencies,
            'currencies' => $currencies,
        ]);
    }

    public function inIndex()
    {
        $legal_currencies = Currency::where('is_legal', 1)->get();
        $currencies = Currency::get();
        return view("admin.transaction.in", [
            'legal_currencies' => $legal_currencies,
            'currencies' => $currencies,
        ]);
    }

    public function outIndex()
    {
        $legal_currencies = Currency::where('is_legal', 1)->get();
        $currencies = Currency::get();
        return view("admin.transaction.out", [
            'legal_currencies' => $legal_currencies,
            'currencies' => $currencies,
        ]);
    }

    public function cnyIndex()
    {
        return view("admin.transaction.cny");
    }

    public function trade()
    {
        return view('admin.transaction.trade');
    }

    public function completeList(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account_number = $request->get('account_number', '');
        $result = TransactionComplete::whereHas('user', function ($query) use ($request) {
            $account_number = $request->get('buy_account_number', '');
            $account_number != '' && $query->where('account_number', 'like', '%' . $account_number . '%');
        })->whereHas('fromUser', function ($query) use ($request) {
            $account_number = $request->get('sell_account_number', '');
            $account_number != '' && $query->where('account_number', 'like', '%' . $account_number . '%');
        })->where(function ($query) use ($request) {
            $legal = $request->input('legal', -1);
            $currency = $request->input('currency', -1);
            $legal != -1 && $query->where('legal', $legal);
            $currency != -1 && $query->where('currency', $currency);
            $start_time = $request->input('start_time', '');
            $end_time = $request->input('end_time', '');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $query->where('create_time', '>=', $start_time);
            }
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $query->where('create_time', '<=', $end_time);
            }
        })->orderBy('id', 'desc')->paginate($limit);
        $sum = $result->sum('number');
        return $this->layuiData($result, $sum);
    }

    public function inList(Request $request)
    {
        $limit = $request->get('limit', 10);
        $result = TransactionIn::whereHas('user', function ($query) use ($request) {
            $account_number = $request->get('account_number', '');
            $account_number != '' && $query->where('account_number', 'like', '%' . $account_number . '%');
        })->where(function ($query) use ($request) {
            $legal = $request->input('legal', -1);
            $currency = $request->input('currency', -1);
            $legal != -1 && $query->where('legal', $legal);
            $currency != -1 && $query->where('currency', $currency);
            $start_time = $request->input('start_time', '');
            $end_time = $request->input('end_time', '');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $query->where('create_time', '>=', $start_time);
            }
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $query->where('create_time', '<=', $end_time);
            }
        })->orderBy('id', 'desc')->paginate($limit);
        $sum = $result->sum('number');
        return $this->layuiData($result, $sum);
    }

    public function outList(Request $request)
    {
        $limit = $request->get('limit', 10);

        $result = TransactionOut::whereHas('user', function ($query) use ($request) {
            $account_number = $request->get('account_number', '');
            $account_number != '' && $query->where('account_number', 'like', '%' . $account_number . '%');
        })->where(function ($query) use ($request) {
            $legal = $request->input('legal', -1);
            $currency = $request->input('currency', -1);
            $legal != -1 && $query->where('legal', $legal);
            $currency != -1 && $query->where('currency', $currency);
            $start_time = $request->input('start_time', '');
            $end_time = $request->input('end_time', '');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $query->where('create_time', '>=', $start_time);
            }
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $query->where('create_time', '<=', $end_time);
            }
        })->orderBy('id', 'desc')->paginate($limit);
        $sum = $result->sum('number');
        return $this->layuiData($result, $sum);
    }

    public function cnyList(Request $request)
    {
        $limit = $request->get('limit', 10);
        $account_number = $request->get('account_number', '');
        $result = new AccountLog();
        if (!empty($account_number)) {
            $users = Users::where('account_number', 'like', '%' . $account_number . '%')->get()->pluck('id');
            $result = $result->whereIn('user_id', $users);
        }
        $types = array(13, 14, 15, 20, 22, 24);
        $result = $result->whereIn('type', $types)->orderBy('id', 'desc')->paginate($limit);
        return $this->layuiData($result);
    }

    public function Leverdeals_show()
    {
        $matches = CurrencyMatch::where('open_lever', 1)->get();
        return view("admin.leverdeals.list", [
            'matches' => $matches,    
        ]);
    }

    //杠杆交易
    public function Leverdeals(Request $request)
    {
        $limit = $request->input("limit", 10);
        $match_id = $request->input('match_id', 0);
        $account_number = $request->input("account_number", '');
        $status = $request->input("status", -1);
        $type = $request->input("type", 0);
        $start_time = $request->input("start_time", '');
        $end_time = $request->input("end_time", '');
        $legal_id = 0;
        $currency_id = 0;
        if ($match_id > 0) {
            $match = CurrencyMatch::find($match_id);
            $legal_id = $match->legal_id ?? 0;
            $currency_id = $match->currency_id ?? 0;
        }
        $order_list = LeverTransaction::when($legal_id > 0, function ($query) use ($legal_id) {
                $query->where('legal', $legal_id);
            })->when($currency_id > 0, function ($query) use ($currency_id) {
                $query->where('currency', $currency_id);
            })->when($account_number != '', function ($query) use ($account_number) {
                $query->whereHas('user', function ($query) use ($account_number) {
                    $query->where('account_number', $account_number)
                        ->orWhere('phone', $account_number)
                        ->orWhere('email', $account_number);
                });
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type);
            })->when($status <> -1, function ($query) use ($status) {
                $query->where('status', $status);
            })->when($start_time != '', function ($query) use ($start_time) {
                $query->where('create_time', '>=', strtotime($start_time));
            })->when($end_time != '', function ($query) use ($end_time) {
                $query->where('create_time', '<=', strtotime($end_time));
            })->orderBy('id', 'desc')
            ->paginate($limit);
        return $this->layuiData($order_list);
    }

    //导出合约交易 团队所有订单excel
    public function csv(Request $request)
    {

        //        $limit = $request->input("limit", "");
        $id = $request->input("id", 0);
        $username = $request->input("phone", '');
        $status = $request->input("status", 10);
        $type = $request->input("type", 0);

        $start = $request->input("start", '');
        $end = $request->input("end", '');
        //        var_dump($id);die;
        $where = [];
        if ($id > 0) {
            $where[] = ['lever_transaction.id', '=', $id];
        }
        //        var_dump($where);die;
        if (!empty($username)) {
            $s = DB::table('users')->where('account_number', $username)->first();
            if ($s !== null) {
                $where[] = ['lever_transaction.user_id', '=', $s->id];
            }
        }

        if ($status != -1 && in_array($status, [LeverTransaction::ENTRUST, LeverTransaction::BUY, LeverTransaction::CLOSED, LeverTransaction::CANCEL, LeverTransaction::CLOSING])) {
            $where[] = ['lever_transaction.status', '=', $status];
        }

        if ($type > 0 && in_array($type, [1, 2])) {
            $where[] = ['type', '=', $type];
        }
        if (!empty($start) && !empty($end)) {
            $where[] = ['lever_transaction.create_time', '>', strtotime($start . ' 0:0:0')];
            $where[] = ['lever_transaction.create_time', '<', strtotime($end . ' 23:59:59')];
        }

        $order_list = TransactionOrdercopy::leftjoin("users", "lever_transaction.user_id", "=", "users.id")->select("lever_transaction.*", "users.phone")->whereIn('lever_transaction.status', [LeverTransaction::ENTRUST, LeverTransaction::BUY, LeverTransaction::CLOSED, LeverTransaction::CANCEL, LeverTransaction::CLOSING])->where($where)->get();

        foreach ($order_list as $key => $value) {
            $order_list[$key]["create_time"] = date("Y-m-d H:i:s", $value->create_time);
            $order_list[$key]["transaction_time"] = date("Y-m-d H:i:s", substr($value->transaction_time, 0, strpos($value->transaction_time, '.')));
            $order_list[$key]["update_time"] = date("Y-m-d H:i:s", substr($value->update_time, 0, strpos($value->update_time, '.')));
            $order_list[$key]["handle_time"] = date("Y-m-d H:i:s", substr($value->handle_time, 0, strpos($value->handle_time, '.')));
            $order_list[$key]["complete_time"] = date("Y-m-d H:i:s", substr($value->complete_time, 0, strpos($value->complete_time, '.')));
        }

        $data = $order_list;

        return Excel::create('合约交易', function ($excel) use ($data) {
            $excel->sheet('合约交易', function ($sheet) use ($data) {
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('ID');
                });
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('用户名');
                });
                $sheet->cell('C1', function ($cell) {
                    $cell->setValue('交易手续费');
                });
                $sheet->cell('D1', function ($cell) {
                    $cell->setValue('隔夜费金额');
                });
                $sheet->cell('E1', function ($cell) {
                    $cell->setValue('交易类型');
                });
                $sheet->cell('F1', function ($cell) {
                    $cell->setValue('当前状态');
                });
                $sheet->cell('G1', function ($cell) {
                    $cell->setValue('原始价格');
                });
                $sheet->cell('H1', function ($cell) {
                    $cell->setValue('开仓价格');
                });
                $sheet->cell('I1', function ($cell) {
                    $cell->setValue('当前价格');
                });



                $sheet->cell('J1', function ($cell) {
                    $cell->setValue('手数');
                });
                $sheet->cell('K1', function ($cell) {
                    $cell->setValue('倍数');
                });
                $sheet->cell('L1', function ($cell) {
                    $cell->setValue('初始保证金');
                });
                $sheet->cell('M1', function ($cell) {
                    $cell->setValue('当前可用保证金');
                });
                $sheet->cell('N1', function ($cell) {
                    $cell->setValue('创建时间');
                });
                $sheet->cell('O1', function ($cell) {
                    $cell->setValue('价格刷新时间');
                });
                $sheet->cell('P1', function ($cell) {
                    $cell->setValue('平仓时间');
                });
                $sheet->cell('Q1', function ($cell) {
                    $cell->setValue('完成时间');
                });

                if (!empty($data)) {
                    foreach ($data as $key => $value) {
                        if ($value['type'] == 1) {
                            $value['type'] = "买入";
                        } else {
                            $value['type'] = "卖出";
                        }
                        if ($value['status'] == 0) {
                            $value['status'] = "挂单中";
                        } elseif ($value['status'] == 1) {
                            $value['status'] = "交易中";
                        } elseif ($value['status'] == 2) {
                            $value['status'] = "平仓中";
                        } elseif ($value['status'] == 3) {
                            $value['status'] = "已平仓";
                        } elseif ($value['status'] == 4) {
                            $value['status'] = "已撤单";
                        }

                        $i = $key + 2;
                        $sheet->cell('A' . $i, $value['id']);
                        $sheet->cell('B' . $i, $value['phone']);
                        $sheet->cell('C' . $i, $value['trade_fee']);
                        $sheet->cell('D' . $i, $value['overnight_money']);
                        $sheet->cell('E' . $i, $value['type']);
                        $sheet->cell('F' . $i, $value['status']);
                        $sheet->cell('G' . $i, $value['origin_price']);
                        $sheet->cell('H' . $i, $value['price']);
                        $sheet->cell('I' . $i, $value['update_price']);

                        $sheet->cell('J' . $i, $value['share']);
                        $sheet->cell('K' . $i, $value['multiple']);
                        $sheet->cell('L' . $i, $value['origin_caution_money']);
                        $sheet->cell('M' . $i, $value['caution_money']);
                        $sheet->cell('N' . $i, $value['create_time']);
                        $sheet->cell('O' . $i, $value['update_time']);
                        $sheet->cell('P' . $i, $value['handle_time']);
                        $sheet->cell('Q' . $i, $value['complete_time']);
                    }
                }
            });
        })->download('xlsx');
    }

    /**
     * 后台强制平仓
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function close()
    {
        $id = Input::get("id");
        if (empty($id)) {
            return $this->error("参数错误");
        }
 
        DB::beginTransaction();
        try {
            $lever_transaction = LeverTransaction::lockForupdate()->find($id);
            if (empty($lever_transaction)) {
                throw new \Exception("数据未找到");
            }
            
            if ($lever_transaction->status != LeverTransaction::TRANSACTION) {
                throw new \Exception("交易状态异常,请勿重复提交");
            }
            $return = LeverTransaction::leverClose($lever_transaction, LeverTransaction::CLOSED_BY_ADMIN);
            if (!$return) {
                throw new \Exception("平仓失败,请重试");
            }
            DB::commit();
            return $this->success("操作成功");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

}
