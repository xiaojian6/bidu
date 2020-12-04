<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\UsersWallet;
use App\UserChat;
use App\Models\Users;
use App\Utils\RPC;
use App\Models\Setting;
use App\Channels\SmsMessage\SmsFactory;
use Illuminate\Support\Facades\Cache;
use App\Models\LeverTransaction;
use App\Http\Controllers\Api\SmsController;

class LeverExpected implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $legal_id;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $legal_id)
    {
        $this->user_id = $user_id;
        $this->legal_id = $legal_id;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // echo 'user_id:' . $this->user_id . PHP_EOL;
            //预期风险率报警提醒
            $user_wallet = UsersWallet::where('user_id', $this->user_id)
                ->where('currency', $this->legal_id)
                ->first();
            if (!$user_wallet) {
                throw new \Exception('钱包不存在');
            }
            //预期风险率
            $lever_expected_rate = Setting::getValueByKey('lever_expected_rate', 0);

            //取风险率
            $hazard_rate = LeverTransaction::getWalletHazardRate($user_wallet);
            $result = bc_comp($hazard_rate, $lever_expected_rate) <= 0;
            if($result){
               
                if (!Cache::has('lever_expected_'.$this->user_id)) {
                    Cache::put('lever_expected_'.$this->user_id, '1',30);
                    //dump($this->user_id);
                    echo '向用户'.$this->user_id.'发送短信'. PHP_EOL;
                    //短信提醒
                    self::sendSms($this->user_id);

                   //测试日志
                //    $path = base_path() . '/storage/logs/lever/';
                //    $filename = date('YmdHis') .$this->user_id. '_expected.log';
                //    file_exists($path) || @mkdir($path);
                //    error_log(date('Y-m-d H:i:s') .$this->user_id. PHP_EOL, 3, $path . $filename);
                    
                }else{
                    echo '----------------------未向用户'.$this->user_id.'发送短信'. PHP_EOL;  
                }

                
                
                
                 
                

            }

        } catch (\Exception $e) {
            echo '文件:' . $e->getFile() . PHP_EOL;
            echo '行号:' . $e->getLine() . PHP_EOL;
            echo '错误:' . $e->getMessage() . PHP_EOL;
            return ;
        }
    }



    //发送短信
    public function sendSms($user_id){
        $user=Users::find($user_id);
        $mobile =$user->phone;
        $email =$user->email;


//        $mobile ="15890603450";
//        $email ="1980817435@qq.com";

        if($mobile){
            //发送短信
            // $address_url="http://guobi.top/api/sms_send";
            // $data=[];
            // $data['user_string']=$mobile;
            // $data['scene']='trade';
            // $data['country_code']=$user->country_code;

            // $lian = RPC::apihttp($address_url,'POST',$data);

            $sender = SmsFactory::getSmsSender($user->country_code == 86 ? 0 : 1);
            $verification_code = self::createSmsCode(4);
            $send_result = $sender->send($mobile, 'yzKD34', ['code'=>$verification_code], $user->country_code);
            if (!$send_result) {
                //发送失败处理逻辑
                echo 'send fails';
            }

        }else{
            //发送邮箱
            $data=[];
            
            $data['user_string']=$email;
            $data['scene']='trade';
            $data['country_code']=$user->country_code;
            $address_url="http://www.bibex.org/api/sendMail_baocang";
            $lian = RPC::apihttp($address_url,'POST',$data);
        }

    }

     /**
     * 生成短信验证码
     * @param int $num  验证码位数
     * @return string
     */
    public function createSmsCode($num = 6)
    {
        //验证码字符数组
        $n_array = range(0, 9);
        //随机生成$num位验证码字符
        $code_array = array_rand($n_array, $num);
        //重新排序验证码字符数组
        shuffle($code_array);
        //生成验证码
        $code = implode('', $code_array);
        return $code;
    }
}
