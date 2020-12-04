<?php

namespace App\BlockChain\Coin\Driver;

use App\BlockChain\Coin\BaseCoin;

class EOS extends BaseCoin
{
    protected $coinCode = 'EOS';

    protected $decimalScale = 8; //小数位数

    protected $generateUri = ''; //生成钱包

    protected $balanceUri = '/wallet/eos/balance'; //查询余额

    protected $transferUri = '/v3/wallet/eos/sendto'; //转账

    protected $transactionUri = '/wallet/eos/tx'; //交易记录

    protected $billsUri = ''; //账单
}
