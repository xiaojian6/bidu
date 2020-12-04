<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Redis;
use PHPMailer\PHPMailer\Exception;
use App\Utils\RPC;
use App\Notifications\{UserRegisterCode, UserLoginCode};
use App\Models\{SmsProject, Setting, Users};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
class SmsController
{
    /**
     * 发送邮箱验证 composer 安装的phpmailer
     */
    public function sendMail()
    {


        $email = config('app.post_mail');
        //  从设置中取出值
        $username = Setting::getValueByKey('phpMailer_username', '');
        $host = Setting::getValueByKey('phpMailer_host', '');
        $password = Setting::getValueByKey('phpMailer_password', '');
        $port = Setting::getValueByKey('phpMailer_port', 465);
        $port == '' && $port = 465;
        //实例化phpMailer
//        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->Username = $username;
            $mail->Password = $password;//去开通的qq或163邮箱中找,这里用的不是邮箱的密码，而是开通之后的一个token
            $mail->setFrom($username, "[比度]");//设置邮件来源  //发件人
            $mail->Subject = "Verification code"; //邮件标题
            $code = $this->createSmsCode(4);
            $mail->MsgHTML('您的邮箱验证码为：' . '【' . $code . '】');   //邮件内容
            $mail->addAddress($email);  //收件人（用户输入的邮箱）
            $res = $mail->send();
            if ($res) {
                $code = md5($code);
                $key=config('app.name');
                $redis = Redis::connection('wallet_redis');
                $redis->select(1);
                $redis->set($key,$code,"ex",115);
                return [
                    'code' => 1
                ];
            } else {
                return ['code'=>0];
            }
//        } catch (\Exception $exception) {
//            return $this->error($exception->getMessage());
//        }

    }

    /**
     * 生成短信验证码
     * @param int $num 验证码位数
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
