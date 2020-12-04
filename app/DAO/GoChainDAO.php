<?php

namespace App\DAO;

use function GuzzleHttp\json_encode;
class GoChainDAO
{
    private static $goChainKey = null;
    private static function getGoChainKey()
    {
        if (empty(self::$goChainKey)) {
            self::$goChainKey = config('gochain.gochain_key');
            if (self::$goChainKey == '') {
                throw new \Exception('GOCHAIN_KEY未设置');
            }
            self::$goChainKey = decrypt(decrypt(self::$goChainKey));
            //解密两次
        }
        return self::$goChainKey;
    }
    /**
     * 生成签名
     *
     * @return array
     */
    public static function makeSign($json_str)
    {
        return md5($json_str . self::getGoChainKey());
    }
    /**
     * 打包转换要发送的数据
     *
     * @param array $params
     * @return void
     */
    public static function pack(&$params)
    {
        if (isset($params['sign'])) {
            unset($params['sign']);
        }
        if (!isset($params['t'])) {
            $params['t'] = time();
        }
        ksort($params, SORT_STRING);
        $json_str = json_encode($params);
        $sign = self::makeSign($json_str);
        $params = ['data' => $json_str, 'sign' => $sign];
    }
    /**
     * 发起一个请求
     *
     * @param string $method 请求类型
     * @param string $uri
     * @param array $params
     * @param string $params_name
     * @param boolean $need_to_sign
     * @return array
     */
    public static function request($method, $uri, $params, $params_name, $need_to_sign = false)
    {
        $client = app('GoChainClient');
        $need_to_sign && self::pack($params);
        $params = [$params_name => $params];
        $response = $client->request($method, $uri, $params);
        $contents = $response->getBody()->getContents();
        return json_decode($contents, true);
    }
    /**
     * 同步用户
     *
     * @param \App\Users $user 用户模型实例
     * @return array
     */
    public static function syncUserInfo($user)
    {
        $uri = '/walletMiddle/AddUser';
        $method = 'POST';
        $params_name = 'form_params';
        $params = ['user_id' => $user->id, 'phone' => $user->phone, 'email' => $user->email];
        $result = self::request($method, $uri, $params, $params_name, true);
        return $result;
    }
    /**
     * 查询钱包
     *
     * @param \App\Users $user 用户模型实例
     * @param \App\Currency $currency 币种模型
     * @return array
     */
    public static function getWalletAddress($user, $currency)
    {
        $uri = "/walletMiddle/GetDrawAddress";
        $method = 'GET';
        $params_name = 'query';
        $params = ['user_id' => $user->id, 'coin_type' => $currency->type];
        $result = self::request($method, $uri, $params, $params_name);
        return $result;
    }
    /**
     * 发送验证码
     *
     * @param \App\Users $user
     * @param \App\Currency $currency
     * @return array
     */
    public static function getVerificationcode($user)
    {
        $uri = "/walletMiddle/SendVerificationcode";
        $method = 'GET';
        $params_name = 'query';
        $params = ['user_id' => $user->id];
        $result = self::request($method, $uri, $params, $params_name);
        return $result;
    }
    /**
     * 绑定提币地址
     *
     * @param \App\Users $user 用户模型实例
     * @param \App\Currency $currency 币种名称
     * @param string $address 提币地址
     * @param string $code 验证码
     * @return array
     */
    public static function bindWithdrawAddress($user, $currency, $address, $verificationcode)
    {
        $uri = "/walletMiddle/BindDrawAddress";
        $method = 'POST';
        $params_name = 'form_params';
        $params = ['user_id' => $user->id, 'coin_name' => $currency->name, 'address' => $address, 'verificationcode' => $verificationcode, 'contract_address' => $currency->contract_address];
        $result = self::request($method, $uri, $params, $params_name, true);
        return $result;
    }
    /**
     * 获取用户的提币地址
     *
     * @param \App\Users $user
     * @param \App\Currency $currency
     * @return array
     */
    public static function getBindWithdrawAddress($user, $currency)
    {
        $uri = "/walletMiddle/GetDrawAddress";
        $method = 'GET';
        $params_name = 'query';
        $params = ['user_id' => $user->id, 'coin_name' => $currency->name, 'contract_address' => $currency->contract_address];
        $result = self::request($method, $uri, $params, $params_name, false);
        return $result;
    }
    /**
     * 用户发起提币
     *
     * @param \App\UsersWalletOut $wallet_out 提币模型实例
     * @return array
     */
    public static function submitUserWithdraw($wallet_out)
    {
        $currency = $wallet_out->currencyCoin;
        $uri = "/walletMiddle/SubmitUserDrawInfo";
        $method = 'POST';
        $params_name = 'form_params';
        $ratio = bc_pow(10, $currency->decimal_scale);
        $number = bc_mul($wallet_out->real_number, $ratio, 0);
        if($currency->contract_address == "")
        {
            $token_address = 0;
        }else
        {
            $token_address = $currency->contract_address;
        }
        $params = ['id' => $wallet_out->id, 'user_id' => $wallet_out->user_id, 'coin_name' => $currency->name, 'coin_type' => $currency->type, 'number' => $number, 'address' => $wallet_out->address, 'contract_address' => $token_address , 'memo' => $wallet_out->memo ?? ''];
        $result = self::request($method, $uri, $params, $params_name, true);
        return $result;
    }
    /**
     * 审核用户提币
     *
     * @param \App\UsersWalletOut $wallet_out 提币模型实例
     * @return array
     */
    public static function auditUserWithdraw($wallet_out)
    {
        $currency = $wallet_out->currencyCoin;
        $uri = "/walletMiddle/AuditUserDrawInfo";
        $fee = $currency->chain_fee;
        $ratio = bc_pow(10, $currency->decimal_scale);
        $number = bc_mul($wallet_out->real_number, $ratio, 0);
        $chain_fee = bc_mul($fee, $ratio, 0);
        $method = 'POST';
        $params_name = 'form_params';
        if($currency->contract_address == "")
        {
            $token_address = 0;
        }else
        {
            $token_address = $currency->contract_address;
        }
        $params = ['id' => $wallet_out->id, 'user_id' => $wallet_out->user_id, 'coin_name' => $currency->name, 'coin_type' => $currency->type, 'number' => $number, 'address' => $wallet_out->address, 'fromaddress' => $currency->total_account, 'privkey' => $currency->origin_key, 'contract_address' => $token_address, 'verificationcode' => $wallet_out->verificationcode, 'fee' => $chain_fee, 'memo' => $wallet_out->memo ?? ''];
        $result = self::request($method, $uri, $params, $params_name, true);
        return $result;
    }
}
?>
