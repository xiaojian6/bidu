<?php

namespace App\Logic;

use Illuminate\Support\Carbon;
use GatewayWorker\Lib\Gateway;
use App\Models\Token;

class SocketLogic
{
    public static function messageHandler($event)
    {
        $message = $event->message;
        $client_id = $event->clientId;
        if (is_json($message)) {
            $message = json_decode($message, true);
            if (isset($message['event'])) {
                $event = ucfirst($message['event']);
                $method = "do{$event}";
                method_exists(self::class, $method) && call_user_func_array([self::class, $method], [$client_id, $message]);
            }
        } else {
            echo date('Y-m-d H:i:s') . ' 接收到未知的消息体:' . PHP_EOL;
            dump($message);
        }
    }

    /**
     * 用户登录
     * 
     * @param string $token
     * @param string $client_id
     * @return boolean
     */
    public static function doLogin($client_id, $message)
    {
        $now = Carbon::now();
        $token = $message['params'];
        $token = Token::where('token', $token)
            ->where('time_out', '>', $now->getTimestamp())
            ->first();
        if (!$token) {
            Gateway::sendToClient($client_id, json_encode([
                    'event' => 'login_result',
                    'code' => -1,
                    'msg' => '登录失败',
                ], JSON_UNESCAPED_UNICODE)
            );
            return false;
        }
        Gateway::bindUid($client_id, $token->user_id);
        Gateway::sendToClient($client_id, json_encode([
                'event' => 'login_result',
                'code' => 1,
                'msg' => '登录成功',
            ], JSON_UNESCAPED_UNICODE)
        );
        return true;
    }

    /**
     * 用户登出
     * 
     * @param string $token
     * @param string $client_id
     * @return boolean
     */
    public static function doLogout($client_id, $message)
    {
        $now = Carbon::now();
        $token = $message['params'];
        $token = Token::where('token', $token)
            ->first();
        if (!$token) {
            Gateway::sendToClient($client_id, json_encode([
                    'event' => 'login_result',
                    'code' => -1,
                    'msg' => '登出失败',
                ], JSON_UNESCAPED_UNICODE)
            );
            return false;
        }
        Gateway::unbindUid($client_id, $token->user_id);
        Gateway::sendToClient($client_id, json_encode([
                'event' => 'login_result',
                'code' => 1,
                'msg' => '登出成功',
            ], JSON_UNESCAPED_UNICODE)
        );
        return true;
    }

    public static function doSub( $client_id, $message)
    {
        $params = $message['params'];
        Gateway::joinGroup($client_id, $params);
    }

    public static function doUnsub($client_id, $message)
    {
        $params = $message['params'];
        Gateway::leaveGroup($client_id, $params);
    }

    public static function sendMsg($data)
    {
        $send_data = json_encode($data);
        $type = $data['type'];
        $to = $data['to'] ?? 0;
        switch ($type) {
            case 'lever_trade':
            case 'lever_closed':
                if (!empty($to)) {
                    // 应先检测有没有订阅
                    $group = "{$type}";
                    $group_uid = Gateway::getUidListByGroup($group);
                    if (in_array($to, $group_uid)) {
                        Gateway::sendToUid($to, $send_data);
                    }
                }
                break;
            case 'kline':
                // no break;
            case 'market_depth':
                $symbol = $data['symbol'];
                $params = array_filter([$type, $symbol]);
                $group = implode('.', $params);
                Gateway::sendToGroup($group, $send_data);
                break;
            case 'daymarket':
                $symbol  = '';
                $params = array_filter([$type, $symbol]);
                $group = implode('.', $params);
                Gateway::sendToGroup($group, $send_data);
                break;                
            default:       
        }
    }
}
