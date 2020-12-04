<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UsersWallet;
use App\DAO\BlockChain;

class ChainController extends Controller
{
    public function collectBalance(Request $request)
    {
        set_time_limit(0);
        $id = $request->get('id', 0);//钱包id
        try {
            $wallet = UsersWallet::find($id);
            throw_unless($wallet, new \Exception('钱包不存在'));
            $result = BlockChain::collect($wallet, true);
            return $this->success('归拢成功,HASH:' . $result['txid']);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function sendFee(Request $request)
    {
        set_time_limit(0);
        $id = $request->get('id', 0);//钱包id
        try {
            $wallet = UsersWallet::find($id);
            throw_unless($wallet, new \Exception('钱包不存在'));
            $result = BlockChain::transferPoundage($wallet, true);
            return $this->success('转入手续费请求成功,请在30分钟之后再进行归拢,HASH:' . $result['txid']);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
