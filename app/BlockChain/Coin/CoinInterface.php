<?php

namespace App\BlockChain\Coin;

interface CoinInterface
{
    /**
     * 生成钱包
     *
     * @param integer $user_id 用户id
     * @return array
     */
    public function makeWallet($user_id);

    /**
     * 转账
     *
     * @param integer $scene 场景
     * @param float $amount 转出金额
     * @param string $receiver 接收者(转入)
     * @param string $sender 发送者(转出)
     * @param string $sender_private_key 发送者私钥(转出方私钥)
     * @param float $fee 矿工手续费
     * @param string $captcha 验证码
     * @return array
     */
    public function transfer($scene, $amount, $receiver, $sender, $sender_private_key, $fee = 0, $captcha = '');

    /**
     * 查询余额
     * 
     * @param string $address 钱包地址
     * @return array
     */
    public function getBalance($address);

    /**
     * 查账单
     *
     * @param string $address 钱包地址
     * @return array
     */
    public function getBills($address);

    /**
     * 查交易
     *
     * @param string $hash
     * @return  array
     */
    public function getTransaction($hash);
}
