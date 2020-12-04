<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\JcExchangeBeau;

class JcIntegralController extends Controller
{
    public function exchange()
    {
        return view('admin.jcintegral.exchange');
    }

    public function exchangeList(Request $request)
    {
        $limit = $request->input('limit', 10);
        $exchange_list = JcExchangeBeau::where(function ($query) use ($request) {
            
        })->orderBy('id', 'desc')->paginate($limit);
        return $this->layuiData($exchange_list);
    }
}
