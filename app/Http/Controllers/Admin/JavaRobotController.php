<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Currency, AutoList};
use GuzzleHttp\Client;
use App\Models\AdminToken;

class JavaRobotController extends Controller
{
    protected static $httpClient = null;

    public static function getHttpClient()
    {
        if (!self::$httpClient) {
            $admin_id = session('admin_id');
            $admin_token = AdminToken::getToken($admin_id);
            self::$httpClient = new Client([
                'headers' => [
                    'Authorization' => $admin_token,
                ]
            ]);
        }
        return self::$httpClient;
    }

    public function index()
    {
        return view('admin.javarobot.index');
    }

    public function lists(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $base_url = config('app.java_match_url');
        $url = $base_url . '/api/auto/auto_list?page=' . $page . '&limit=' . $limit;
        $client = self::getHttpClient();
        $response = $client->get($url);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    public function add(Request $request)
    {
        $id = $request->input('id', 0);
        $currencies = Currency::where('is_display', 1)->get();
        $legals = Currency::where('is_display', 1)->where('is_legal', 1)->get();
        $result = AutoList::find($id);
        return view('admin.javarobot.add', [
            'currencies' => $currencies,
            'legals' => $legals,
            'result' => $result,
        ]);
    }

    public function postAdd(Request $request)
    {
        $base_url = config('app.java_match_url');
        $url = $base_url . '/api/auto/add_or_update';
        $params = $request->all();
        $client = self::getHttpClient();
        $response = $client->post($url, [
            'form_params' => $params,
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    public function changeStart(Request $request)
    {
        $id = $request->input('id', '');
        $symbol = $request->input('symbol', '');

        $base_url = config('app.java_match_url');
        $url = $base_url . '/api/auto/' . $symbol;

        $client = self::getHttpClient();
        $response = $client->post($url, [
            'form_params' => [
                'id' => $id,
            ],
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    /**
     * 删除机器人
     *
     * @param Request $request
     * @return array
     */
    public function del(Request $request)
    {
        $id = $request->input('id', '');

        $base_url = config('app.java_match_url');
        $url = $base_url . '/api/auto/auto_del';

        $client = self::getHttpClient();
        $response = $client->post($url, [
            'form_params' => [
                'id' => $id,
            ],
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    /**
     * 批量撤掉机器人订单
     *
     * @param Request $request
     * @return array
     */
    public function cancel(Request $request)
    {
        $id = $request->input('id', '');
        $base_url = config('app.java_match_url');
        $url = $base_url . '/api/auto/auto_transaction_dels';

        $client = self::getHttpClient();
        $response = $client->post($url, [
            'form_params' => [
                'id' => $id,
            ],
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
//        var_dump($result);die;
        return $result;
    }
}
