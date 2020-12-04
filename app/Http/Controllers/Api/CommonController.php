<?php

namespace App\Http\Controllers\Api;

use App\Events\BlockChainTxEvent;
use Illuminate\Http\Request;
class CommonController extends Controller
{
    public function block(Request $request)
    {
        // 这里对数据来源进行验证，例如验证IP，或者加上签名机制等，防止数据伪造
        $block = $request->all();
        event(new BlockChainTxEvent($block));
        return $this->success('ok');
    }
}
