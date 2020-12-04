<?php

namespace App\BlockChain\Coin\Driver;

use App\BlockChain\Coin\BaseCoin;

class ERC20 extends BaseCoin
{
    protected $coinCode = 'ERC20/ETH';

    protected $decimalScale = 18; //小数位数

    protected $generateUri = '/v3/wallet/address'; //生成钱包

    protected $balanceUri = '/wallet/eth/tokenbalance'; //查询余额

    protected $transferUri = '/v3/wallet/eth/tokensendto'; //转账

    protected $transactionUri = '/wallet/eth/tx'; //交易记录

    protected $billsUri = ''; //账单
}
