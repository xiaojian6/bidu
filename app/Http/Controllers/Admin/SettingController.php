<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settingList = Setting::all()->toArray();
        $setting = [];
        foreach ($settingList as $key => $value) {
            $setting[$value['key']] = $value['value'];
        }
        // var_dump($setting);
        return view('admin.setting.base', ['setting' => $setting]);
    }

    public function dataSetting()
    {
        $settingList = Setting::all()->toArray();
        $setting = [];
        foreach ($settingList as $key => $value) {
            $setting[$value['key']] = $value['value'];
        }
        return view('admin.setting.data', ['setting' => $setting]);
    }

    public function postAdd(Request $request)
    {
        $data = $request->all();
        try {
            // 先给支付密码验证场景一个默认值
            $paypassword_scenes = unserialize(Setting::getValueByKey('paypassword_scenes', 'a:0:{}'));
            foreach ($paypassword_scenes as $key => $value) {
                Setting::updateOrCreate(['key' => "{$key}_need_password"], [
                    'value' => 0,
                ]);
            }
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['key' => $key], [
                    'value' => $value ?? '',
                ]);
            }
            return $this->success('操作成功');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function generalaccount()
    {
        $contract_address = Setting::getValueByKey('contract_address');
        $total_account_address = Setting::getValueByKey('total_account_address');
        $total_account_key = Setting::getValueByKey('total_account_key');

        $total_account_token = 0;
        if (!empty($contract_address) && !empty($total_account_address)) {

            $url = "https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=" . $contract_address . "&address=" . $total_account_address . "&tag=latest&apikey=579R8XPDUY1SHZNEZP9GA4FEF1URNC3X45";
            $content = RPC::apihttp($url);
            $content = @json_decode($content, true);

            if (isset($content["message"]) && $content["message"] == "OK") {
                $total_account_token = $content["result"] / 1000000000000000000;


            }

        }

        $results = array(
            'contract_address' => $contract_address,
            'total_account_address' => $total_account_address,
            'total_account_key' => $total_account_key,
            'total_account_token' => $total_account_token
        );
        return view('admin.setting.generalaccount', ['results' => $results]);
    }

    public function dogeneralaccount(Request $request)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'contract_address':
                    break;
                case 'total_account_address':
                    break;
                case 'total_account_key':
                    break;
            }
            Setting::updateValueByKey($key, $value);
        }
        return $this->success('操作成功');
    }
}
