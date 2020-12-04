<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{Currency, CurrencyMatch, CurrencyPlate, Setting, UsersWallet, HuobiSymbol,Label};

class LabelController extends Controller
{
    public function index()
    {
        return view('admin.label.index');
    }

    public function add(Request $request)
    {
        $id = $request->get('id', 0);
        if (empty($id)) {
            $result = new Label();
        } else {
            $result = Currency::find($id);
        }
        return view('admin.label.add')->with('result', $result);
    }

    public function postAdd(Request $request)
    {
        $id = $request->get('id', 0);
        $name = $request->get('name', '');
        $is_show_label = $request->get('is_show_label', 0) ?? 0;
        $label=new Label();
        try {
            DB::beginTransaction();
            $has = Label::where('name', $name)->first();

            if (empty($id) && !empty($has)) {
                throw new \Exception('标签 ' . $name . ' 已存在');
            }
            $label->name = $name;
            $label->is_show_label = $is_show_label;
            $label->save();//保存币种
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
        $result = new Label();
        $result = $result->where("name","!=","NULL")->orderBy('id', 'desc')->paginate($limit);
        return $this->layuiData($result);
    }

    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $acceptor = Label::find($id);
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
        $currency = Label::find($id);
        if (empty($currency)) {
            return $this->error('参数错误');
        }
        if ($currency->is_show_label == 1) {
            $currency->is_show_label = 0;
        } else {
            $currency->is_show_label = 1;
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
        $legal = Label::find($legal_id);
        $matchs = $legal->quotation()->paginate($limit);
        return $this->layuiData($matchs);
    }

    public function addMatch($legal_id)
    {
        $is_legal = Label::where('id', $legal_id)->value('is_legal');
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
        $is_legal = Label::where('id', $legal_id)->value('is_legal');
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
