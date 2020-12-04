<?php

namespace App\BlockChain\Coin\Driver;

use App\BlockChain\Coin\BaseCoin;

class USDT extends BaseCoin
{
    protected $coinCode = 'USDT@BTC'; //币种标识

    protected $decimalScale = 8; //小数位数

    protected $generateUri = '/v3/wallet/address'; //生成钱包

    protected $balanceUri = '/wallet/usdt/balance'; //查询余额

    protected $transferUri = '/v3/wallet/usdt/sendto'; //转账

    protected $transactionUri = '/wallet/usdt/tx'; //交易记录

    protected $billsUri = ''; //账单
}
