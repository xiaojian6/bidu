<?php

namespace App\Console\Commands\Market;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;
//use App\CurrencyMatch;
use App\Utils\Market\HuobiMarket;

use App\Models\CurrencyMatch;

class ImportFromHuobi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:import:from:huobi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //exit('禁止运行');
        $to_host = config('elasticsearch.hosts');
        $to_client = self::getEsearchClient($to_host);
        $huobi_matchs = CurrencyMatch::getHuobiMatchs();
        $periods = ['1min', '5min', '15min', '30min', '60min', '1day', '1week', '1mon'];
        echo 1;
        foreach ($periods as $period) {
            echo 2;
            foreach ($huobi_matchs as $key => $match) {
                $base_currency = $match->currency_name;
                $quote_currency = $match->legal_name;
                $type = strtoupper($base_currency . '.' . $quote_currency) . '.' . $period;
                $result = HuobiMarket::getHistoryKline($match->match_name, $period, 300);
                echo 3;
                if($result)
                {
                    echo 4;
                    $result = json_decode(json_encode($result->data), true);
                    $params = [];
                    foreach ($result as $value) {
                        unset($value['count']);
                        $value['period'] = $period;
                        if(strtoupper($base_currency)=='QTUM'){
                            $base_currency='eptc';
                        }
                        $value['base-currency'] = $base_currency;
                        $value['quote-currency'] = $quote_currency;
                        $params['body'][] = [
                            'index' => [
                                '_index' => 'market.kline',
                                '_type' => 'doc',
                                '_id' =>  $type . '.' . $value['id'],
                            ]
                        ];
                        $params['body'][] = $value;
                    }
                    echo '正在导入' . $match->match_name . ' ' . $period . '行情' . count($result) . '条' . PHP_EOL;
                    $result = $to_client->bulk($params);
                }


            }
        } 
    }

    /**
     * 获得一个ElasticsearchClient实例
     *
     * @return \Elasticsearch\Client
     */
    public static function getEsearchClient($hosts)
    {
        $es_client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        return $es_client;
    }
}
