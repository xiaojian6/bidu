<?php

namespace App\BlockChain\Coin\Driver;

use App\BlockChain\Coin\BaseCoin;

class BTC extends BaseCoin
{
    protected $coinCode = 'BTC';

    protected $decimalScale = 8; //小数位数

    protected $generateUri = '/v3/wallet/address'; //生成钱包

    protected $balanceUri = '/wallet/btc/balance'; //查询余额

    protected $transferUri = '/v3/wallet/btc/sendto'; //转账

    protected $transactionUri = '/wallet/btc/tx'; //交易记录

    protected $billsUri = ''; //账单
}
