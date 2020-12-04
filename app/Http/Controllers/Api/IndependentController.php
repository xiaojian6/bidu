<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\BindJcUser;
use App\Models\Currency;
use App\DAO\JcDAO;
use App\DAO\UserDAO;
use App\Models\Token;
use App\Models\Users;
use App\Models\UsersWallet;
use App\Utils\JcIntegral;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Utils\RPC;
use Session;
use App\Notifications\UserRegisterCode;

class IndependentController extends Controller
{
    protected static $requestClient = null;

    public function points()
    {
        $password = Input::get('password', '');
        $user_string = Input::get('user_string', '');
        $type = Input::get('type', 'mobile');

        if (empty($password) || empty($user_string)) return $this->error('参数错误');
        $JcIntegral = new JcIntegral($user_string, $password);
        $result = $JcIntegral->getIntegral();
        if ($result['code'] == 1001) {
            return $this->error('用户名不存在');
        }
        if ($result['code'] == 1002) {
            return $this->error('密码不正确');
        }
        if ($type == '') {
            return $this->error('参数错误');
        }
        DB::beginTransaction();
        try {
            $user = Users::getByString($user_string);
            if (empty($user)) {
                $bool = self::register($type, $user_string, $password);
                if ($bool == false) {
                    throw new \Exception('失败');
                }
            }
            $user = Users::getByString($user_string);
            $token = Token::setToken($user->id);
            if ($token == false) {
                throw new \Exception('失败');
            }
            $bjbind = BindJcUser::where('user_id', $user->id)->first();
            if (empty($bjbind)) {
                $bind = $this->Bind($user_string, $password);
                if ($bind !== true) {
                    throw new \Exception($bind);
                }
            } else {
                $bjbind->user_name = $user_string;
                $bjbind->password = $password;
                $bjbind->save();
            }
            DB::commit();
            return $this->success($token);

        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    public function Bind($user_string = "", $password = "")
    {
        $user = Users::getByAccountNumber($user_string);
        if (empty($user_string) || empty($password)) {
            return '参数错误';
        }
        //$user = Users::find($user_id);
        $user = json_decode(json_encode($user), true);
            //$user=$user[0];
            //var_dump($user_id);exit();
        try {
            if ($user['type'] == 0) {
                throw new \Exception('您的账号未开启此功能');
            }
            BindJcUser::unguard();
            $bind_jc_user = BindJcUser::firstOrCreate([
                'user_id' => $user['id']
            ], [
                'user_name' => $user_string,
                'password' => $password,
                'status' => 1,
            ]);
            if (!$bind_jc_user) {
                throw new \Exception('绑定失败');
            }
            $result = $bind_jc_user->fill([
                'user_name' => $user_string,
                'password' => $password,
            ])->save();
            if (!$result) {
                throw new \Exception('更新绑定信息失败');
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        finally {
            BindJcUser::reguard();
        }
    }

    //注册
    public function register($type = "", $user_string = '', $password = '', $country_code = '86', $nationality = "China")
    {


        if (empty($type) || empty($user_string) || empty($password)) {
            return $this->error('参数错误');
        }
        $country_code = str_replace('+', '', $country_code);

        if (mb_strlen($password) < 6 || mb_strlen($password) > 16) {
            return $this->error('密码只能在6-16位之间');
        }

        $user = Users::getByString($user_string);
        if (!empty($user)) {
            return $this->error('账号已存在');
        }
        $parent_id = 1;
        $salt = Users::generate_password(4);
        $users = new Users();
        $users->password = Users::MakePassword($password);
        $users->parent_id = $parent_id;
        $users->type = 1;
        $users->account_number = $user_string;
        $users->country_code = $country_code; //更新国家代码
        $users->nationality = $nationality; //更新国籍
        if ($type == "mobile") {
            $users->phone = $user_string;
        } else {
            $users->email = $user_string;
        }
        $users->head_portrait = URL("mobile/images/user_head.png");
        $users->time = time();
        $users->extension_code = Users::getExtensionCode();
        DB::beginTransaction();
        try {
            $users->parents_path = $str = UserDAO::getRealParentsPath($users);//生成parents_path     tian  add

            //代理商节点id。标注该用户的上级代理商节点。这里存的代理商id是agent代理商表中的主键，并不是users表中的id。
            $users->agent_note_id = Agent::reg_get_agent_id_by_parentid($parent_id);

            $users->save();//保存到user表中
            $currency = Currency::all();

            $address_url = config('lbxchain.wallet_api') . $users->id;
            //var_dump($address_url);
            $address = RPC::apihttp($address_url);
            $address = @json_decode($address, true);
            //var_dump($address);die;
            foreach ($currency as $key => $value) {
                $userWallet = new UsersWallet();
                $userWallet->user_id = $users->id;
                if ($value->type == 'btc' || $value->type == 'usdt') {
                    $userWallet->address = $address["contentbtc"];
                    $userWallet->private = encrypt($address["privatebtc"]);
                } else {
                    $userWallet->address = $address["content"];
                    $userWallet->private = encrypt($address["private"]);
                }
                $userWallet->currency = $value->id;
                $userWallet->create_time = time();
                $userWallet->save();//默认生成所有币种的钱包
            }
            // $url = 'http://www.paybal.world/api/import_users';
//             $post_api = RPC::apihttp($url,'POST',['username'=>$user_string,'password'=>$user->password,'salt'=>$salt,'parent_phone'=>$parent_phone]);
            // $post_api = @json_decode($post_api,true);
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }
    }

    protected static function getClient()
    {
        if (self::$requestClient == null) {
            self::$requestClient = new Client();
        }
        return self::$requestClient;
    }
}