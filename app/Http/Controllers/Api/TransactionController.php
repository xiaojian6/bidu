<?php

namespace App\Http\Controllers\Api;

use Session;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Events\TransactionCommitted;
use GuzzleHttp\Client;
use App\Models\{CurrencyMatch, CurrencyQuotation, UserChat, AccountLog, Currency, Token, Transaction, TransactionComplete, TransactionIn, TransactionOut, Users, UsersWallet};

class TransactionController extends Controller
{
    //正在买入记录
    public function TransactionInList(Request $request)
    {
        $user_id = Users::getUserId();
        $legal_id = $request->input('legal_id', 0);
        $currency_id = $request->input('currency_id', 0);
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $limit = Input::get('limit', 10);
        $page = Input::get('page', 1);
        $transactionIn = TransactionIn::where('user_id', $user_id)
            ->when($legal_id > 0, function ($query) use ($legal_id) {
                $query->where('legal', $legal_id);
            })
            ->when($currency_id > 0, function ($query) use ($currency_id) {
                $query->where('currency', $currency_id);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
        if (empty($transactionIn)) {
            return $this->error('您还没有交易记录');
        }
        return $this->success(array(
            "list" => $transactionIn->items(), 'count' => $transactionIn->total(),
            "page" => $page, "limit" => $limit
        ));
    }

    //正在卖出记录
    public function TransactionOutList(Request $request)
    {
        $user_id = Users::getUserId();
        $legal_id = $request->input('legal_id', 0);
        $currency_id = $request->input('currency_id', 0);
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $limit = Input::get('limit', 10);
        $page = Input::get('page', 1);
        $transactionOut = TransactionOut::where('user_id', $user_id)
            ->when($legal_id > 0, function ($query) use ($legal_id) {
                $query->where('legal', $legal_id);
            })
            ->when($currency_id > 0, function ($query) use ($currency_id) {
                $query->where('currency', $currency_id);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
        if (empty($transactionOut)) {
            return $this->error('您还没有交易记录');
        }
        return $this->success(array(
            "list" => $transactionOut->items(), 'count' => $transactionOut->total(),
            "page" => $page, "limit" => $limit
        ));
    }

    //交易完成记录
    public function TransactionCompleteList()
    {
        $user_id = Users::getUserId();
        $limit = Input::get('limit', 10);
        $page = Input::get('page', 1);
        if (empty($user_id)) {
            return $this->error('参数错误');
        }
        $TransactionComplete = TransactionComplete::where('user_id', $user_id)
            ->orwhere('from_user_id', $user_id)
            ->orderBy('id', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
        if (empty($TransactionComplete)) {
            return $this->error('您还没有交易记录');
        }
        foreach ($TransactionComplete->items() as $key => &$value) {
            if ($value['user_id'] == $user_id) {
                $value['type'] = 'in';
            } else {
                $value['type'] = 'out';
            }
        }
        return $this->success(array(
            "list" => $TransactionComplete->items(), 'count' => $TransactionComplete->total(),
            "page" => $page, "limit" => $limit
        ));
    }

    //取消交易
    public function TransactionDel()
    {
        $user_id = Users::getUserId();
        $id = Input::get('id', '');
        $type = Input::get('type', '');//in 买入交易 out卖出交易
        if (empty($user_id) || empty($id) || empty($type)) {
            return $this->error('参数错误');
        }
        DB::beginTransaction();
        if ($type == 'in') {//取消法币锁定
            try {
                $transactionIn = TransactionIn::where('user_id', $user_id)->find($id); //限定只能操作自己发布的
                if (!$transactionIn) {
                    throw new \Exception('非法操作,不能撤回非自己发布的信息');
                }
                $currency_match = CurrencyMatch::where('currency_id', $transactionIn->currency)
                    ->where('legal_id', $transactionIn->legal)
                    ->first();
                if (!$currency_match) {
                    return $this->error('交易对不存在');
                }
                $user_wallet = UsersWallet::where('user_id', $user_id)
                    ->where('currency', $transactionIn->legal)
                    ->lockForUpdate()
                    ->first();
                if (!$user_wallet) {
                    throw new \Exception('钱包不存在');
                }
                $amount = bc_mul($transactionIn->price, $transactionIn->number, 8);
                if (bc_comp($user_wallet->lock_change_balance, $amount) < 0) {
                    throw new \Exception('非法操作:(');
                }
                $result = change_wallet_balance($user_wallet, 2, -$amount, AccountLog::TRANSACTIONIN_IN_DEL, "取消挂买". $currency_match->symbol . ",解除锁定" . $currency_match->legal_name, true);
                if ($result !== true) {
                    throw new \Exception($result);
                }
                $result = change_wallet_balance($user_wallet, 2, $amount, AccountLog::TRANSACTIONIN_IN_DEL, "取消挂买". $currency_match->symbol .",退回" . $currency_match->legal_name);
                if ($result !== true) {
                    throw new \Exception($result);
                }
                $result = TransactionIn::destroy($id);
                if ($result < 1) {
                    throw new \Exception('取消挂买交易失败');
                }
                DB::commit();
                return $this->success('取消成功');
            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage());
            }
        } elseif ($type == 'out') {
            try {
                $transactionOut = TransactionOut::where('user_id', $user_id)->find($id); //限定只能操作自己发布的
                if (!$transactionOut) {
                    throw new \Exception('非法操作');
                }
                $currency_match = CurrencyMatch::where('currency_id', $transactionOut->currency)
                    ->where('legal_id', $transactionOut->legal)
                    ->first();
                if (!$currency_match) {
                    return $this->error('交易对不存在');
                }
                $user_wallet = UsersWallet::where('user_id', $user_id)
                    ->where('currency', $transactionOut->currency)
                    ->lockForUpdate()
                    ->first();
                if (!$user_wallet) {
                    throw new \Exception('钱包不存在');
                }
                /*
                //计算撤回手续费
                $quantity = $transactionOut->number * $transactionOut->rate / 100;
                $result = change_wallet_balance($user_wallet, 2, $quantity, AccountLog::MATCH_TRANSACTION_CANCEL_SELL_FEE, '取消挂卖' . $currency_match->symbol . '撤回手续费{撤回数量:' . $transactionOut->number . ',费率:' . $transactionOut->rate . '%}');
                if ($result !== true) {
                    throw new \Exception($result);
                }
                */

                if (bc_comp($user_wallet->lock_change_balance, $transactionOut->number) < 0) {
                    throw new \Exception('非法操作');
                }

                $result = change_wallet_balance($user_wallet, 2, -$transactionOut->number, AccountLog::TRANSACTIONIN_OUT_DEL, "取消挂卖" . $currency_match->symbol . ",解除锁定" . $currency_match->currency_name, true);
                if ($result !== true) {
                    throw new \Exception($result);
                }

                $result = change_wallet_balance($user_wallet, 2, $transactionOut->number, AccountLog::TRANSACTIONIN_OUT_DEL, "取消挂卖" . $currency_match->symbol . ",退回" . $currency_match->currency_name);
                if ($result !== true) {
                    throw new \Exception($result);
                }

                $del_result = TransactionOut::destroy($id);
                if ($del_result < 1) {
                    throw new \Exception('取消挂卖失败');
                }
                DB::commit();
                return $this->success('取消成功');
            } catch (\Exception $ex) {
                DB::rollBack();
                return $this->error($ex->getMessage());
            }
        } else {
            return $this->error('类型错误');
        }
    }

    /**
     * 挂卖
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function out()
    {
        $user_id = Users::getUserId();
        $price = Input::get("price");
        $num = Input::get("num");
        $legal_id = Input::get("legal_id");
        $currency_id = Input::get("currency_id");
        if (empty($user_id) || empty($price) || empty($num) || empty($legal_id) || empty($currency_id)) {
            return $this->error("参数错误");
        }
        $currency_match = CurrencyMatch::where('legal_id', $legal_id)
            ->where('currency_id', $currency_id)
            ->first();
        if (!$currency_match) {
            return $this->error('指定交易对不存在');
        }
        if ($currency_match->open_transaction != 1) {
            return $this->error('您没有开通该交易对的交易功能');
        }
        $exchange_rate = $currency_match->exchange_rate;
        $quantity = bc_div(bc_mul($num, $exchange_rate), 100);//交易利率
        $real_quantity = $num + $quantity;
        $has_num = 0;
        $user = Users::find($user_id);

        $legal = Currency::where("is_display", 1)
            ->where("id", $legal_id)
            ->where("is_legal", 1)
            ->first();
        $currency = Currency::where("is_display", 1)
            ->where("id", $currency_id)
            ->first();
        if (empty($user) || empty($legal) || empty($currency)) {
            return $this->error("数据未找到");
        }

        //使用JAVA进行撮合交易
        $use_java_match_trade = config('app.use_java_match_trade', 0);
        if ($use_java_match_trade) {
            $java_match_url = config('app.java_match_url', '');
            $request_client = new Client();
            $response = $request_client->post($java_match_url . '/api/transaction/out', [
                'headers' => [
                    'Authorization' => Token::getToken(),
                ],
                'form_params' => [
                    'legal_id' => $legal_id,
                    'currency_id' => $currency_id,
                    'price' => $price,
                    'num' => $num,
                ],
            ]);

            $result = $response->getBody()->getContents();
            $result = json_decode($result);
            if (!isset($result->type) || $result->type != 'ok') {
                return $this->error($result->message);
            }
            DB::commit();
            return $this->success("操作成功");
        }

        try {
            DB::beginTransaction();
            $user_currency = UsersWallet::where("user_id", $user_id)
                ->where("currency", $currency_id)
                ->lockForUpdate()
                ->first();
            if (empty($user_currency)) {
                throw new \Exception("请先添加钱包");
            }
            if (bc_comp($price, '0') <= 0 || bc_comp($num, '0') <= 0) {
                throw new \Exception("价格和数量必须大于0");
            }
            if (bc_comp($user_currency->change_balance, $real_quantity) < 0) {
                throw new \Exception("您的余额不足,请确保足够支付手续费{$exchange_rate}%({$quantity})");
            }
            if (bc_comp($user_currency->lock_change_balance, '0') < 0) {
                throw new \Exception("您的冻结资金异常，禁止挂卖");
            }

            //挂卖先扣手续费
            $result = change_wallet_balance($user_currency, 2, -$quantity, AccountLog::MATCH_TRANSACTION_SELL_FEE, '挂卖扣除手续费,挂卖数量:' . $num . ',费率:' . $exchange_rate . '%');
            if ($result !== true) {
                throw new \Exception($result);
            }
            //查找价格高于等于当前卖出价格的所有买入委托
            $in = TransactionIn::where("price", ">=", $price)
                ->where("currency", $currency_id)
                ->where("legal", $legal_id)
                ->where("number", ">", "0")
                ->orderBy('price', 'desc')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get();
            //dd($in);
            if (count($in) > 0) {
                foreach ($in as $i) {
                    if (bc_comp($has_num, $num) < 0) {
                        $shengyu_num = bc_sub($num, $has_num);
                        $this_num = 0;
                        if (bc_comp($i->number, $shengyu_num) > 0) {
                            $this_num = $shengyu_num;
                        } else {
                            $this_num = $i->number;
                        }
                        $has_num = bc_add($has_num, $this_num);
                        if (bc_comp($this_num, '0') > 0) {
                            TransactionOut::transaction($i, $this_num, $user, $user_currency, $legal_id, $currency_id);
                        }
                    } else {
                        break;
                    }
                }
            }
            $num = bc_sub($num, $has_num);
            if (bc_comp($num, '0') > 0) {
                $out = new TransactionOut();
                $out->user_id = $user_id;
                $out->price = $price;
                $out->number = $num;
                $out->currency = $currency_id;
                $out->legal = $legal_id;
                $out->create_time = time();
                $out->rate = $exchange_rate;
                $out->save();
                //提交卖出记录扣除交易币
                $result = change_wallet_balance($user_currency, 2, -$num, AccountLog::TRANSACTIONOUT_SUBMIT_REDUCE, '提交挂卖' . $currency_match->symbol . '扣除');
                if ($result !== true) {
                    throw new \Exception($result);
                }
                //提交卖出记录(增加冻结)
                $result = change_wallet_balance($user_currency, 2, $num, AccountLog::TRANSACTIONOUT_SUBMIT_REDUCE, '提交挂卖' . $currency_match->symbol . '冻结', true);
                if ($result !== true) {
                    throw new \Exception($result);
                }
            }
            if ($currency_match->market_from != 2) {
                Transaction::pushNews($currency_id, $legal_id);
            }
            DB::commit();
            return $this->success("操作成功");
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    /**
     * 挂买
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function in()
    {
        $user_id = Users::getUserId();
        $price = Input::get("price");
        $num = Input::get("num");
        $legal_id = Input::get("legal_id");
        $currency_id = Input::get("currency_id");

        if (empty($user_id) || empty($price) || empty($num) || empty($legal_id) || empty($currency_id)) {
            return $this->error("参数错误");
        }
        $currency_match = CurrencyMatch::where('legal_id', $legal_id)
            ->where('currency_id', $currency_id)
            ->first();
        if (!$currency_match) {
            return $this->error('指定交易对不存在');
        }
        if ($currency_match->open_transaction != 1) {
            return $this->error('您没有开通该交易对的交易功能');
        }
        $has_num = 0;
        $legal = Currency::where("is_display", 1)
            ->where("id", $legal_id)
            ->where("is_legal", 1)
            ->first();
        $currency = Currency::where("is_display", 1)
            ->where("id", $currency_id)
            ->first();
        $user = Users::find($user_id);
        if (empty($user) || empty($legal) || empty($currency)) {
            return $this->error("数据未找到");
        }

        if (bc_comp($price, '0') <= 0 || bc_comp($num, '0') <= 0) {
            return $this->error("价格和数量必须大于0");
        }

        //使用JAVA进行撮合交易
        $use_java_match_trade = config('app.use_java_match_trade', 0);
        if ($use_java_match_trade) {
            $java_match_url = config('app.java_match_url', '');
            $request_client = new Client();
            $response = $request_client->post($java_match_url . '/api/transaction/in', [
                'headers' => [
                    'Authorization' => Token::getToken(),
                ],
                'form_params' => [
                    'legal_id' => $legal_id,
                    'currency_id' => $currency_id,
                    'price' => $price,
                    'num' => $num,
                ],
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result);
            if (!isset($result->type) || $result->type != 'ok') {
                return $this->error($result->message);
            }
            DB::commit();
            return $this->success("操作成功");
        }

        try {
            DB::beginTransaction();
            //买方法币钱包
            $user_legal = UsersWallet::where("user_id", $user_id)
                ->where("currency", $legal_id)
                ->lockForUpdate()
                ->first();
            $all_balance = bc_mul($price, $num);
            if (bc_comp($user_legal->change_balance, $all_balance) < 0) {
                throw new \Exception('余额不足');
            }

            //查找所有价格小于等于当前价格的卖出委托
            $out = TransactionOut::where("price", "<=", $price)
                ->where("number", ">", "0")
                ->where("currency", $currency_id)
                ->where("legal", $legal_id)
                ->lockForUpdate()
                ->orderBy('price', 'asc')
                ->orderBy('id', 'asc')
                ->get();
            if (count($out) > 0) {
                foreach ($out as $o) {
                    if (bc_comp($has_num, $num) < 0) {
                        $shengyu_num = bc_sub($num, $has_num);
                        $this_num = 0;
                        if (bc_comp($o->number, $shengyu_num) > 0) {
                            $this_num = $shengyu_num;
                        } else {
                            $this_num = $o->number;
                        }
                        $has_num = bc_add($has_num, $this_num);
                        if (bc_comp($this_num, '0') > 0) {
                            TransactionIn::transaction($o, $this_num, $user, $legal_id, $currency_id);
                        }
                    } else {
                        break;
                    }
                }
            }

            $remain_num = bcsub($num, $has_num); //匹配后的剩余数量
            if (bc_comp($remain_num, '0') > 0) {
                $in = new TransactionIn();
                $in->user_id = $user_id;
                $in->price = $price;
                $in->number = $remain_num;
                $in->currency = $currency_id;
                $in->legal = $legal_id;
                $in->create_time = time();
                $in->save();
                $all_balance = bc_mul($price, $remain_num);
                //提交买入记录扣除
                $result = change_wallet_balance($user_legal, 2, -$all_balance, AccountLog::TRANSACTIONIN_SUBMIT_REDUCE, '提交挂买' . $currency_match->symbol . '扣除');
                if ($result !== true) {
                    throw new \Exception($result);
                }
                //提交买入记录扣除冻结
                $result = change_wallet_balance($user_legal, 2, $all_balance, AccountLog::TRANSACTIONIN_SUBMIT_REDUCE, '提交挂买' . $currency_match->symbol . '冻结', true);
                if ($result !== true) {
                    throw new \Exception($result);
                }
            }
            if ($currency_match->market_from != 2) {
                Transaction::pushNews($currency_id, $legal_id);
            }
            
            DB::commit();
            return $this->success("操作成功");
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->error($ex->getMessage());
        }
    }

    public function deal()
    {
        $user_id = Users::getUserId();

        $legal_id = Input::get("legal_id");
        $currency_id = Input::get("currency_id");

        if (empty($legal_id) || empty($currency_id)) {
            return $this->error("参数错误");
        }
        $legal_currency = Currency::find($legal_id);
        $currency_match = CurrencyMatch::where('legal_id', $legal_id)
            ->where('currency_id', $currency_id)
            ->first();
        if (!$currency_match) {
            return $this->error('指定symbols对不存在');
        }
        $in = TransactionIn::with(['legalcoin', 'currencycoin'])
            ->where("number", ">", 0)
            ->where("currency", $currency_id)
            ->where("legal", $legal_id)
            ->groupBy('currency', 'legal', 'price')
            ->orderBy('price', 'desc')
            ->select([
                'currency',
                'legal',
                'price',
            ])->selectRaw('sum(`number`) as `number`')
            ->limit(5)
            ->get()
            ->toArray();
        $out = TransactionOut::with(['legalcoin', 'currencycoin'])
            ->where("number", ">", 0)
            ->where("currency", $currency_id)
            ->where("legal", $legal_id)
            ->groupBy('currency', 'legal', 'price')
            ->orderBy('price', 'asc')
            ->select([
                'currency',
                'legal',
                'price',
            ])->selectRaw('sum(`number`) as `number`')
            ->limit(5)
            ->get()
            ->toArray();
        $in = array_map(function ($item) {
            $item['number'] = number_format($item['number'], 2, '.', '');
            $item['price'] = number_format($item['price'], 6, '.', '');
            return $item;
        }, $in);

        $out = array_map(function ($item) {
            $item['number'] = number_format($item['number'], 2, '.', '');
            $item['price'] = number_format($item['price'], 6, '.', '');
            return $item;
        }, $out);

        krsort($out);
        $out_data = array();
        foreach ($out as $o) {
            array_push($out_data, $o);
        }

        $complete = TransactionComplete::orderBy('id', 'desc')->where("currency", $currency_id)->where("legal", $legal_id)->take(15)->get();

        $last_price = 0;
        //从行情取最新价
        $last = CurrencyQuotation::where('legal_id', $legal_id)
            ->where('currency_id', $currency_id)
            ->first();
        if (!$last) {
            $last = TransactionComplete::orderBy('id', 'desc')
                ->where("currency", $currency_id)
                ->where("legal", $legal_id)
                ->first();
            if (!empty($last)) {
                $last_price = $last->price;
            }
        } else {
            $last && $last_price = $last->now_price;
        }
        $user_legal = 0;
        $user_currency = 0;
        if (!empty($user_id)) {
            $legal = UsersWallet::where("user_id", $user_id)->where("currency", $legal_id)->first();
            if ($legal) {
                $user_legal = $legal->change_balance;
            }
            $currency = UsersWallet::where("user_id", $user_id)->where("currency", $currency_id)->first();
            if ($currency) {
                $user_currency = $currency->change_balance;
            }
        }

        return $this->success([
            "in" => $in,
            "out" => $out_data,
            'legal_currency' => $legal_currency,
            //all_legal"=>$all_legal,
            //"all_currency"=>$all_currency,
            "last_price" => $last_price,
            "user_legal" => $user_legal,
            "user_currency" => $user_currency,
            "complete" => $complete,
            'currency_match' => $currency_match,
        ]);
    }

    public function introduction(Request $request)
    {
        $currency_id = $request->get('currency_id', "");
        if (empty($currency_id)) {
            return $this->error('参数错误');
        }
        $currency = Currency::where('id', $currency_id)->select()->get();
        $data = [];
        if (empty($currency_id)) {
            $data['status'] = 0;
            $data['introduction'] = 0;
        } else {
            $data['status'] = 1;
            $data['introduction'] = $currency;
        }
        return $this->success($data);
    }
}
