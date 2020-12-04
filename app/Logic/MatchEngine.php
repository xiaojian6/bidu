<?php


namespace App\DAO;

use App\Console\Commands\Match;
use App\Jobs\SendMarket;
use App\Jobs\UpdateBalance;
use App\Models\AccountLog;
use App\Models\Currency;
use App\Models\CurrencyMatch;
use App\Models\MarketHour;
use App\Models\Setting;
use App\Models\TransactionComplete;
use App\Models\TransactionIn;
use App\Models\TransactionOut;
use App\Models\UsersWallet;
use Illuminate\Support\Facades\DB;
use Workerman\Worker;

class MatchEngine
{
    /**
     * @var CurrencyMatch
     */
    protected $currencyMatch;

    /**
     * @param $worker Worker
     */
    public function onWorkerStart($worker)
    {
        try {
            //必须重新连接,否则子进程还是用的父进程的连接
            DB::reconnect();

            $this->currencyMatch = CurrencyMatch::find(Match::$currency_match_ids[$worker->id]);

            $baseCurrency = $this->currencyMatch->currency;
            $quoteCurrency = $this->currencyMatch->legal;

            if (!$baseCurrency) {
                $this->info('交易币不存在');
                return;
            }
            if (!$quoteCurrency) {
                $this->info('计价币不存在');
                return;
            }

            $this->info("交易对{$this->currencyMatch->symbol}启动");
            $worker->name = "Match Engine {$this->currencyMatch->symbol}";

            $this->match();
        } catch (\Throwable $t) {
            $this->info($t->getMessage());
        }
    }

    /**撮合
     *
     * @param $currency
     * @param $legal
     */
    public function match()
    {
        $count = 0;
        do {
            try {
                DB::transaction(function () use (&$count) {
                    $start = microtime(true);

                    $order = $this->getOrder();
                    if (!$order) {
                        return;
                    }
                    $in = $order['in'];
                    $out = $order['out'];

                    //判断应该按照谁的价格
                    $price = $this->getPrice($in, $out);

                    //判断数量
                    $number = $this->getNumber($in, $out);

                    //计算金额
                    $base_balance = $number;
                    $quote_balance = bc($number, '*', $price);
                    //扣过手续费的金额
                    $dec_fee_base_balance = $this->getInFee($base_balance, $in_fee);
                    $dec_fee_quote_balance = $this->getOutFee($quote_balance, $out_fee);

                    //加减金额
                    $this->changeBalance($in, $out, $base_balance, $quote_balance, $dec_fee_base_balance,
                        $dec_fee_quote_balance);

                    //写入完成记录
                    TransactionComplete::create([
                        'in_user_id' => $in->user_id,
                        'out_user_id' => $out->user_id,
                        'price' => $price,
                        'number' => $number,
                        'currency_match_id' => $this->currencyMatch->id,
                        'in_fee' => $in_fee,
                        'out_fee' => $out_fee,
                    ]);

                    $this->sendES($number, $price);

                    $count++;
                    sleep(1);
                    $end = microtime(true);
                    $use_time = round($end - $start, 5);
//                    $this->info("价格:{$price}");
//                    $this->info("数量:{$number}");
                    $this->info("{$this->currencyMatch->symbol}已处理{$count}单");
                    $min_order = round(1 / $use_time, 2);
                    $this->info("本单用时{$use_time}秒,合每秒{$min_order}单");
                    $this->info("--------------------------------------------");
                });

            } catch (\Throwable $t) {
                sleep(1);
                $this->info($t->getMessage());
            }
        } while (true);
    }

    /**获取挂单
     *
     * @param $legal
     * @param $currency
     *
     * @return array|false
     */
    public function getOrder()
    {
        $in = TransactionIn::where('legal', $this->currencyMatch->legal()->value('id'))
            ->where('currency', $this->currencyMatch->currency()->value('id'))
            ->orderBy('create_time')
            ->first();
        $in = TransactionIn::lockForUpdate()->find($in->id ?? 0);

        $out = TransactionOut::where('legal', $this->currencyMatch->legal()->value('id'))
            ->where('currency', $this->currencyMatch->currency()->value('id'))
            ->where('price', '<=', $in->price ?? 0)->first();
        $out = TransactionOut::lockForUpdate()->find($out->id ?? 0);

        if (!$out) {
            $out = TransactionOut::where('legal', $this->currencyMatch->legal()->value('id'))
                ->where('currency', $this->currencyMatch->currency()->value('id'))
                ->orderBy('create_time')
                ->first();
            $out = TransactionOut::lockForUpdate()->find($out->id ?? 0);

            $in = TransactionIn::where('legal', $this->currencyMatch->legal()->value('id'))
                ->where('currency', $this->currencyMatch->currency()->value('id'))
                ->where('price', '>=', $out->price ?? 0)->first();
            $in = TransactionIn::lockForUpdate()->find($in->id ?? 0);
        }

        if (!$out || !$in) {
            $this->info("{$this->currencyMatch->symbol}无匹配单");
            sleep(1);
            return false;
        }

        return [
            'in' => $in,
            'out' => $out
        ];
    }

    /**获取买入手续费,返回扣过手续费的金额
     *
     */
    public function getInFee($number, &$fee)
    {
        $rate = Setting::getValueByKey('match_fee_rate', 0);
        $rate = bc($rate, '*', 0.01);
        $fee = bc($number, '*', $rate);
        $number = bc($number, '-', $fee);
        return $number;
    }

    /**获取卖出手续费,返回扣过手续费的金额
     *
     */
    public function getOutFee($number, &$fee)
    {
        $rate = $this->currencyMatch->exchange_out_fee_rate;
        $rate = bc($rate, '*', 0.01);
        $fee = bc($number, '*', $rate);
        $number = bc($number, '-', $fee);
        return $number;
    }

    /**获取挂单匹配价格
     *
     * @param $in  TransactionIn
     * @param $out TransactionOut
     *
     * @return float
     */
    public function getPrice($in, $out)
    {
        $price = 0;
        if ($in->created_time > $out->created_time) {
            $price = $in->price;
        } elseif ($in->created_time < $out->created_time) {
            $price = $out->price;
        } elseif ($in->created_time == $out->created_time) {
            $price = $out->price;
        }
        return $price;
    }

    /**获取挂单匹配数量并且对挂单进行更改
     *
     * @param $in  TransactionIn
     * @param $out TransactionOut
     *
     * @return float
     * @throws \Exception
     */
    public function getNumber($in, $out)
    {
        $number = 0;
        if ($in->number > $out->number) {
            $in->number -= $out->number;
            $in->save();
            $number = $out->number;
            $out->delete();
        } elseif ($in->number < $out->number) {
            $out->number -= $in->number;
            $out->save();
            $number = $in->number;
            $in->delete();
        } elseif ($in->number == $out->number) {
            $number = $in->number;
            $in->delete();
            $out->delete();
        }
        return $number;
    }

    /**改变用户金额
     *
     * @param TransactionIn  $in                    买入挂单
     * @param TransactionOut $out                   卖出挂单
     * @param float|string   $base_balance          需要减少的金额
     * @param float|string   $quote_balance         需要减少的计价币
     * @param float|string   $dec_fee_base_balance  扣过手续费的金额
     * @param float|string   $dec_fee_quote_balance 扣过手续费的计价币金额
     *
     * @throws \Exception
     */
    public function changeBalance($in, $out, $base_balance, $quote_balance, $dec_fee_base_balance, $dec_fee_quote_balance)
    {
        //给买方加交易币金额,减少计价币

        $inUserCurrencyWallet = UsersWallet::where('user_id', $in->user_id)
            ->where('currency', $this->currencyMatch->currency()->value('id'))
            ->lockForUpdate()->first();
        $inUserLegalWallet = UsersWallet::where('user_id', $in->user_id)
            ->where('currency', $this->currencyMatch->legal()->value('id'))
            ->lockForUpdate()->first();

        change_wallet_balance($inUserCurrencyWallet, 2, $dec_fee_base_balance,
            AccountLog::TRANSACTIONIN_REDUCE_ADD,'撮合交易成交增加');
        change_wallet_balance($inUserLegalWallet, 2, -$quote_balance,
            AccountLog::TRANSACTIONIN_REDUCE_ADD,'撮合交易成交扣除冻结', true);

        //给卖方加计价币金额,减少交易币
        $outUserCurrencyWallet = UsersWallet::where('user_id', $out->user_id)
            ->where('currency', $this->currencyMatch->currency()->value('id'))
            ->lockForUpdate()->first();
        $outUserLegalWallet = UsersWallet::where('user_id', $out->user_id)
            ->where('currency', $this->currencyMatch->legal()->value('id'))
            ->lockForUpdate()->first();

        change_wallet_balance($outUserLegalWallet, 2, $dec_fee_quote_balance,
            AccountLog::TRANSACTIONIN_SELLER_ADD,'撮合交易成交增加');
        change_wallet_balance($outUserCurrencyWallet, 2, -$base_balance,
            AccountLog::TRANSACTIONIN_REDUCE_ADD,'撮合交易成交扣除冻结', true);
    }

    /**推送行情
     *
     */
    public function sendMarket($kline_data, $number)
    {
        foreach ($kline_data as $data) {
            //推送最高最低
            $send_data = [
                'close' => $data['close'],
                'currency_id' => $this->currencyMatch->currency()->value('id'),
                'currency_name' => $data['base-currency'],
                'high' => $data['high'],
                'legal_id' => $this->currencyMatch->legal()->value('id'),
                'legal_name' => $data['quote-currency'],
                'low' => $data['low'],
                'match_id' => $this->currencyMatch->id,
                'open' => $data['open'],
                'period' => $data['period'],
                'symbol' => $data['base-currency'] . '/' . $data['quote-currency'],
                'time' => $data['id'],
                'type' => "kline",
                'volume' => $number,
            ];

            SendMarket::dispatch($send_data);
        }
    }

    /**写入ES
     *
     */
    public function sendES($number, $price)
    {
        $kline_data = MarketHour::batchEsearchMarket($this->currencyMatch->currency_name, $this->currencyMatch->legal_name,
            $price, now()->timestamp);
        $this->sendMarket($kline_data, $number);
    }

    /**打印信息
     *
     * @param $str
     */
    public function info($str)
    {
        echo $str . PHP_EOL;
    }

}
