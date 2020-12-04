<?php

/**
 * swl
 *
 * 20180705
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\{Currency, LbxHash, UsersWallet};
use App\DAO\BlockChainDAO;

class UpdateHashStatus extends Command
{
    protected $signature = 'update_hash_status';
    protected $description = '更新链上哈希状态';

    public function handle()
    {
        //type业务类型:0.归拢,1.提币 2.打入手续费  status 0 未处理  1处理成功   2处理失败
        $start_time = Carbon::today()->subDays(3);
        $datas = LbxHash::whereIn("type", [0, 2])
            ->where("status", 0)
            ->where('created_at', '>=', $start_time)
            ->get();
        $this->comment("start");
        foreach ($datas as $d) {
            $this->updateHashStatus($d);
        }
        $this->comment("end");
    }

    public function updateHashStatus($data) //测试计划任务更新链上余额
    {   
        if (empty($data->txid)) {
            return false;
        }
        echo 'id:' . $data->id . ',正在检测Hash:' . $data->txid . PHP_EOL;
        try {
            DB::beginTransaction();
            $user_wallet = UsersWallet::lockForUpdate()->find($data->wallet_id);
            $currency = Currency::find($user_wallet->currency);
            if (empty($currency)) {
                throw new \Exception('币种不存在');
            }
            $currency_type = $currency->type;
            if (!in_array($currency_type, ["usdt", "btc", "eth", "erc20"])) {
                throw new \Exception('不支持的币种');
            }
            $currency_type == 'erc20' &&  $currency_type = 'eth';

            if ($data->type == 0) {
                // 只有归拢的才查询链上余额并更新余额
                try {
                    BlockChainDAO::updateWalletBalance($user_wallet);
                } catch (\Exception $ex) {
                    echo $ex->getMessage() . PHP_EOL;
                }
            } elseif ($data->type == 2) {
                // 打入手续费由于代币和主链币种不同,所以得改成从主链查
                if ($currency->type == 'usdt') {
                    $currency_type = 'btc';
                } elseif ($currency->type == 'erc20') {
                    $currency_type = 'eth';
                }
            } elseif ($data->type == 3) {
                // 提币哈希
            }

            $chain_client = app('LbxChainServer');
            $uri = "/wallet/{$currency_type}/tx";
            $response = $chain_client->request('get', $uri, [
                'query' => [
                    'hash' => $data->txid,
                ]
            ]);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);

            //记录请求日志
            file_exists(base_path('storage/logs/blockchain/')) || @mkdir(base_path('storage/logs/blockchain/'));
            // Log::useDailyFiles(base_path('storage/logs/blockchain/blockchain'), 7);
            // Log::critical($uri, $result);
            if (!isset($result['code'])) {
                throw new \Exception('请求发生异常...');
            }
            if ($result['code'] == 0) {
                $zoom_ratio = bc_pow(10, $currency->decimal_scale);
                $fact_fee = bc_div($result['data']['fee'], $zoom_ratio);
                if ($data->type == 0) {
                    //当业务类型为归拢时,要减去子账号的链上余额
                    $new_balance = bc_sub($user_wallet->old_balance, $data->amount);
                    $user_wallet->old_balance = bc_comp($new_balance, '0') > 0 ? $new_balance: 0;
                    //如果是主币还应更新掉主币消耗的手续费
                    if (in_array($currency->type, ['eth', 'btc'])) {
                        $new_balance = bc_sub($user_wallet->old_balance, $fact_fee);
                        $user_wallet->old_balance = bc_comp($new_balance, '0') > 0 ? $new_balance: 0;
                    }
                } elseif ($data->type == 2) {
                    //如果代币和主币的手续费钱包分开了,代币就没必要做任何处理了,主币无须打入手续费
                }
                $data->status = 1; //0 未处理  1处理成功   2处理失败
                $data->fact_fee = $fact_fee;
                $user_wallet->save();
            } elseif ($result['code'] == 1) {
                //1为等待链上确认中 ，等待下次处理
                throw new \Exception('等待链上确认中...');
            } elseif ($result['code'] == 2 || $result['code'] == 3) {
                //失败的情况
                $data->status = 2;
            } elseif ($result['code'] == 4) {
                //判断时间是否大于72小时
                $now = Carbon::now();
                if ($now->gt($data->created_at) && $now->diffInHours($data->created_at) >= 72) {
                    $data->status = 2;
                } else {
                    throw new \Exception('等待链上打包...');
                }
            } else {
                throw new \Exception('请求结果未知...');
            }
            $data->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->comment($ex->getFile());
            $this->comment($ex->getLine());
            $this->comment($ex->getMessage());
        }
    }
}