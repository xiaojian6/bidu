<?php

namespace App\BlockChain\Coin\Driver;

use App\BlockChain\Coin\BaseCoin;

class ETH extends BaseCoin
{
    protected $coinCode = 'ETH';

    protected $decimalScale = 18; //小数位数

    protected $generateUri = '/v3/wallet/address'; //生成钱包

    protected $balanceUri = '/wallet/eth/balance'; //查询余额

    protected $transferUri = '/v3/wallet/eth/sendto'; //转账

    protected $transactionUri = '/wallet/eth/tx'; //交易记录

    protected $billsUri = ''; //账单
}
