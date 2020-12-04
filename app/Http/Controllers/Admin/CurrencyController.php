<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Currency, CurrencyMatch, CurrencyPlate, Setting, UsersWallet, HuobiSymbol,Label};

class CurrencyController extends Controller
{
    public function index()
    {
        return view('admin.currency.index');
    }

    public function add(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            $result = new Currency();
        } else {
            $result = Currency::find($id);
        }
        return view('admin.currency.add')->with('result', $result);
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', 0);
        $name = $request->get('name', '');
        $sort = $request->get('sort', 0) ?? 0;
        $logo = $request->get('logo', '') ?? '';
        $type = $request->get('type', '') ?? '';
        $make_wallet = $request->get('make_wallet', 0) ?? 0;
        $is_legal = $request->get('is_legal', 0) ?? 0;
        $is_lever = $request->get('is_lever', 0) ?? 0;
        $is_match = $request->get('is_match', 0) ?? 0;

        $is_change = $request->get('is_change', 0) ?? 0;

        $min_number = $request->get('min_number', 0) ?? 0;
        $max_number = $request->get('max_number', 0) ?? 0;
        $rate = $request->get('rate', 0) ?? 0;
        $total_account = $request->get('total_account', '') ?? '';
        $key = $request->get('key', '') ?? '';
        $contract_address = $request->get('contract_address', '') ?? '';
        $decimal_scale = $request->get('decimal_scale', 18);
        $chain_fee = $request->get('chain_fee', 0);
        $verificationcode = $request->input('verificationcode', '');
        $clone_currency_k = $request->get('clone_currency_k', '') ?? '';
        //自定义验证错误信息
        $messages = [
            'required' => ':attribute 为必填字段',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sort' => 'required',
            'type' => 'required',
        ], $messages);

        try {
            DB::beginTransaction();
            $projectname = config('app.name');
            //如果验证不通过
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            $currency = Currency::findOrNew($id);

            $has = Currency::where('name', $name)->first();

            if (empty($id) && !empty($has)) {
                throw new \Exception('币种 ' . $name . ' 已存在');
            }
            if(!empty($clone_currency_k)){
                $has_symbol = HuobiSymbol::where('base-currency',$clone_currency_k)->get();
                if(count($has_symbol) == 0){
                    throw new \Exception('行情暂不支持该币种');
                }
            }
            $chain_client = app('LbxChainServer');

            if ($currency->total_account != $total_account) {
                if ($verificationcode == '') {
                    throw new \Exception('请先填写验证码再操作');
                } else {
                    $uri = '/v3/wallet/changeaddress';
                    $response = $chain_client->request('post', $uri, [
                        'form_params' => [
                            'projectname' => $projectname,
                            'coin' => strtoupper($currency->type),
                            'address' => $total_account,
                            'verificationcode' => $verificationcode,
                        ],
                    ]);
                    $result = json_decode($response, true);
                    if (!isset($result['code']) || $result['code'] != 0) {
                        throw new \Exception($result['msg'] ?? '请求发生错误');
                    }

                }
            }


            $currency->name = $name;
            $currency->sort = intval($sort);
            $currency->logo = $logo;
            $currency->is_legal = $is_legal;
            $currency->is_lever = $is_lever;
            $currency->is_match = $is_match;

            $currency->is_change = $is_change;

            $currency->make_wallet = $make_wallet;
            $currency->min_number = $min_number;
            $currency->max_number = $max_number;
            $currency->rate = $rate;
            $currency->total_account = $total_account;
            $currency->decimal_scale = $decimal_scale;
            $currency->chain_fee = $chain_fee; //链上手续费
            $currency->clone_currency_k = $clone_currency_k; //克隆币种行情
            $auto_encrypt_private = Setting::getValueByKey('auto_encrypt_private', 1);
            if ($key != '********') {
                if ($auto_encrypt_private) {
                    $uri = '/v3/wallet/encrypt';
                    $response = $chain_client->request('post', $uri, [
                        'form_params' => [
                            'projectname' => $projectname,
                            'p' => $key,
                        ],
                    ]);
                    $result = json_decode($response, true);
                    if (!isset($result['code']) || $result['code'] != 0) {
                        throw new \Exception($result['msg'] ?? '请求发生错误');
                    }
                         $currency->key = $result['data']['k'];

                        $redis = Redis::connection('wallet_redis');
                        $redis->select(7);
                        $keys = strtoupper($currency->type);
                        $key_base_64 = base64_encode($key);
                        switch ($keys){
                            case "ERC20":
                                $redis->set("e2",$key_base_64);
                                break;
                            case "ETH":
                                $redis->set("e",$key_base_64);
                                break;
                            case "BTC":
                                $redis->set("b",$key_base_64);
                                break;
                            case "USDT":
                                $redis->set("u",$key_base_64);
                                break;
                        }
                } else {
                    $currency->key = '';
                }
            }
            $currency->contract_address = $contract_address;
            $currency->type = $type;
            $currency->is_display = 1;
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
        $limit = $request->get('limit', 10);
//        $account_number = $request->get('account_number','');
        $result = new Currency();
        $result = $result->orderBy('sort', 'asc')->orderBy('id', 'desc')->paginate($limit);
        foreach ($result as $k => $v) {
            $legal_balance = UsersWallet::where('currency', $v->id)->sum('legal_balance');
            $lock_legal_balance = UsersWallet::where('currency', $v->id)->sum('lock_legal_balance');
            $change_balance = UsersWallet::where('currency', $v->id)->sum('change_balance');
            $lock_change_balance = UsersWallet::where('currency', $v->id)->sum('lock_change_balance');
            $lever_balance = UsersWallet::where('currency', $v->id)->sum('lever_balance');
            $lock_lever_balance = UsersWallet::where('currency', $v->id)->sum('lock_lever_balance');
            $sum = bcadd($legal_balance, $lock_legal_balance);
            $sum = bcadd($sum, $change_balance);
            $sum = bcadd($sum, $lock_change_balance);
            $sum = bcadd($sum, $lever_balance);
            $sum = bcadd($sum, $lock_lever_balance);
            $v->sum = $sum;
            $result[$k] = $v;
        }
        return $this->layuiData($result);
    }

    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $acceptor = Currency::find($id);
        if (empty($acceptor)) {
            return $this->error('无此币种');
        }
        try {
            $acceptor->delete();
            return $this->success('删除成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function isDisplay(Request $request)
    {
        $id = $request->get('id', 0);
        $currency = Currency::find($id);
        if (empty($currency)) {
            return $this->error('参数错误');
        }
        if ($currency->is_display == 1) {
            $currency->is_display = 0;
        } else {
            $currency->is_display = 1;
        }
        try {
            $currency->save();
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function executeCurrency(Request $request)
    {
        $id = $request->get('id', 0);
        Artisan::queue('execute_currency', [
            'id' => $id
        ])->onQueue('currency:execute');
        return $this->success('开始在后台执行上币脚本');
    }

    /**
     * 交易对显示
     *
     * @return void
     */
    public function match()
    {
        return view('admin.currency.match');
    }

    public function matchList(Request $request)
    {
        $legal_id = $request->route('legal_id');
        $limit = $request->input('limit', 10);
        $legal = Currency::find($legal_id);
        $matchs = $legal->quotation()->paginate($limit);
        return $this->layuiData($matchs);
    }

    public function addMatch($legal_id)
    {
        $is_legal = Currency::where('id', $legal_id)->value('is_legal');
        if (!$is_legal) {
            abort(403, '指定币种不是法币,不能添加交易对');
        }
        $currencies = Currency::where('id', '<>', $legal_id)->get();
        $market_from_names = CurrencyMatch::enumMarketFromNames();
        $plates = CurrencyPlate::all();
        $labels=Label::all();
        return view('admin.currency.match_add')
            ->with('currencies', $currencies)
            ->with('market_from_names', $market_from_names)
            ->with('plates', $plates)
            ->with('labels', $labels);
    }

    public function postAddMatch(Request $request, $legal_id)
    {
        $is_legal = Currency::where('id', $legal_id)->value('is_legal');
        if (!$is_legal) {
            return $this->error('指定币种不是法币,不能添加交易对');
        }
        $label_id = $request->input('label_id', 0) ?? 0;
        $plate_id = $request->input('plate_id', 0) ?? 0;
        $currency_id = $request->input('currency_id', 0) ?? 0;
        $is_display = $request->input('is_display', 1) ?? 1;
        $market_from = $request->input('market_from', 0) ?? 0;
        $open_transaction = $request->input('open_transaction', 0);
        $open_lever = $request->input('open_lever', 0);
        $lever_share_num = $request->input('lever_share_num', 1);
        $spread = $request->input('spread', 0);
        $sort = $request->input('sort', 0);
        $overnight = $request->input('overnight', 0);
        $lever_trade_fee = $request->input('lever_trade_fee', 0);
        $lever_min_share = $request->input('lever_min_share', 0);
        $lever_max_share = $request->input('lever_max_share', 0);
        $exchange_rate = $request->input('exchange_rate', 0);
        if ($exchange_rate < 0 || $exchange_rate > 100) {
            return $this->error('撮合交易手续费率必须大于0小于100');
        }
        if ($lever_trade_fee < 0 || $lever_trade_fee > 100) {
            return $this->error('杠杆交易手续费率必须大于0小于100');
        }
        //检测交易对是否已存在
        $exist = CurrencyMatch::where('currency_id', $currency_id)
            ->where('legal_id', $legal_id)
            ->first();
        if ($exist) {
            return $this->error('对应交易对已存在');
        }
        CurrencyMatch::unguard();
        $currency_match = CurrencyMatch::create([
            'plate_id' => $plate_id,
            'legal_id' => $legal_id,
            'currency_id' => $currency_id,
            'is_display' => $is_display,
            'market_from' => $market_from,
            'open_transaction' => $open_transaction,
            'open_lever' => $open_lever,
            'lever_share_num' => $lever_share_num,
            'lever_trade_fee' => $lever_trade_fee,
            'sort' => $sort,
            'spread' => $spread,
            'overnight' => $overnight,
            'lever_min_share' => $lever_min_share,
            'lever_max_share' => $lever_max_share,
            'exchange_rate' => $exchange_rate,
            'label_id' => $label_id,
            'create_time' => time(),
        ]);
        CurrencyMatch::reguard();
        return isset($currency_match->id) ? $this->success('添加成功') : $this->error('添加失败');
    }

    public function editMatch($id)
    {
        $currency_match = CurrencyMatch::find($id);
        if (!$currency_match) {
            abort(403, '指定交易对不存在');
        }
        $market_from_names = CurrencyMatch::enumMarketFromNames();
        $plates = CurrencyPlate::all();
        $labels=Label::all();
        $currencies = Currency::where('id', '<>', $currency_match->legal_id)->get();
        $var = compact('currency_match', 'currencies', 'market_from_names', 'plates','labels');
        return view('admin.currency.match_add', $var);
    }

    public function postEditMatch(Request $request, $id)
    {
        $label_id = $request->input('label_id', 0) ?? 0;
        $plate_id = $request->input('plate_id', 0) ?? 0;
        $currency_id = $request->input('currency_id', 0) ?? 0;
        $is_display = $request->input('is_display', 1) ?? 1;
        $market_from = $request->input('market_from', 0) ?? 0;
        $open_transaction = $request->input('open_transaction', 0);
        $open_lever = $request->input('open_lever', 0);
        $lever_share_num = $request->input('lever_share_num', 1);
        $spread = $request->input('spread', 0);
        $sort = $request->input('sort', 0);
        $recommended = $request->input('recommended', 0);
        $overnight = $request->input('overnight', 0);
        $lever_trade_fee = $request->input('lever_trade_fee', 0);
        $lever_min_share = $request->input('lever_min_share', 0);
        $lever_max_share = $request->input('lever_max_share', 0);
        $exchange_rate = $request->input('exchange_rate', 0);
        if ($exchange_rate < 0 || $exchange_rate > 100) {
            return $this->error('撮合交易手续费率必须大于0小于100');
        }
        if ($lever_trade_fee < 0 || $lever_trade_fee > 100) {
            return $this->error('杠杆交易手续费率必须大于0小于100');
        }
        $currency_match = CurrencyMatch::find($id);
        if (!$currency_match) {
            abort(403, '指定交易对不存在');
        }
        CurrencyMatch::unguard();
        $result = $currency_match->fill([
            'plate_id' => $plate_id,
            'currency_id' => $currency_id,
            'is_display' => $is_display,
            'market_from' => $market_from,
            'open_transaction' => $open_transaction,
            'open_lever' => $open_lever,
            'lever_share_num' => $lever_share_num,
            'lever_trade_fee' => $lever_trade_fee,
            'spread' => $spread,
            'sort' => $sort,
            'recommended' => $recommended,
            'overnight' => $overnight,
            'lever_min_share' => $lever_min_share,
            'lever_max_share' => $lever_max_share,
            'exchange_rate' => $exchange_rate,
            'label_id' => $label_id,
            'create_time' => time(),
        ])->save();
        CurrencyMatch::reguard();
        return $result ? $this->success('保存成功') : $this->error('保存失败');
    }

    public function delMatch($id)
    {
        $result = CurrencyMatch::destroy($id);
        return $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}
