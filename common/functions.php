<?php

use Illuminate\Support\Facades\DB;
use App\Models\AccountLog;
use App\Models\WalletLog;

defined('DECIMAL_SCALE') || define('DECIMAL_SCALE', 8);
bcscale(DECIMAL_SCALE);

/**高精度计算
 *
 * @param string $num1
 * @param string $symbol
 * @param string $num2
 * @param int    $decimals
 *
 * @return bool|string|null
 */
function bc($num1, $symbol, $num2, $decimals = DECIMAL_SCALE)
{
    return \App\Utils\BC::compute($num1, $symbol, $num2, $decimals);
}

/**
 * 高精度相加
 *
 * @param string  $left_operand
 * @param string  $right_operand
 * @param integer $out_scale
 *
 * @return string
 */
function bc_add($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcadd', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度相减
 *
 * @param string  $left_operand
 * @param string  $right_operand
 * @param integer $out_scale
 *
 * @return string
 */
function bc_sub($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcsub', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度相乘
 *
 * @param string  $left_operand
 * @param string  $right_operand
 * @param integer $out_scale
 *
 * @return string
 */
function bc_mul($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcmul', $left_operand, $right_operand, $out_scale);
}

function bc_minus(&$left_operand, $out_scale = DECIMAL_SCALE)
{
    return $left_operand = bc_method('bcmul', $left_operand, '-1', $out_scale);
}

/**
 * 高精度相除
 *
 * @param string  $left_operand
 * @param string  $right_operand
 * @param integer $out_scale
 *
 * @return string
 */
function bc_div($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcdiv', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度取余
 *
 * @param string  $left_operand
 * @param string  $right_operand
 * @param integer $out_scale
 *
 * @return string
 */
function bc_mod($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcmod', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度比较两个数值大小
 *
 * @param string $left_operand
 * @param string $right_operand
 *
 * @return integer
 */
function bc_comp($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bccomp', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度与零比较
 *
 * @param string $left_operand
 *
 * @return integer
 */
function bc_comp_zero($left_operand, $out_scale = DECIMAL_SCALE)
{
    $right_operand = '0';
    return bc_method('bccomp', $left_operand, $right_operand, $out_scale);
}

/**
 * 高精度次幂运算
 *
 * @param string $left_operand
 * @param string $right_operand
 *
 * @return string
 */
function bc_pow($left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    return bc_method('bcpow', $left_operand, $right_operand, $out_scale);
}

function bc_method($method_name, $left_operand, $right_operand, $out_scale = DECIMAL_SCALE)
{
    $left_operand = sctonum($left_operand, $out_scale);
    $right_operand = sctonum($right_operand, $out_scale);
    if (!is_string($left_operand) || !is_string($right_operand)) {
        throw new \Exception('高精度运算参数类型必须是字符串');
    }
    $left_operand == '' && $left_operand = '0';
    $right_operand == '' && $right_operand = '0';
    $left_operand = set_format_decimal($left_operand, DECIMAL_SCALE);
    $method_name != 'bcpow' && $right_operand = set_format_decimal($right_operand, DECIMAL_SCALE);
    $result = call_user_func($method_name, $left_operand, $right_operand, $out_scale);
    return $method_name != 'bccomp' ? set_format_decimal($result, $out_scale) : $result;
}

function set_format_decimal($number, $decimal_scale)
{
    $suffix = str_repeat('0', $decimal_scale);
    $dot_num = substr_count($number, '.');
    if ($dot_num > 1) {
        throw new \Exception('不是一个有效数值');
    }
    // 防止小数位数参数不是整数
    $decimal_scale = intval($decimal_scale);
    if ($decimal_scale < 0) {
        throw new \Exception('小数位数错误');
    }
    $decimal = '';
    if ($dot_num == 0) {
        $integer = $number;
    } else {
        list($integer, $decimal) = explode('.', $number, 2);
    }
    $decimal = substr($decimal . $suffix, 0, $decimal_scale);
    return $integer . ($decimal_scale == 0 ? '' : '.') . $decimal;
}

/**
 * 科学计数法转字符串
 *
 * @param float   $num 数值
 * @param integer $double
 *
 * @return string
 */
function sctonum($num, $double = DECIMAL_SCALE)
{
    if (false !== stripos($num, "e")) {
        $a = explode("e", strtolower($num));
        return bcmul(strval($a[0]), bcpow('10', $a[1], $double), $double);
    } else {
        return strval($num);
    }
}

/**
 * 改变钱包余额
 *
 * @param App\UsersWallet &$wallet           用户钱包模型实例
 * @param integer          $balance_type     1.法币,2.币币交易,3.杠杆交易 4 锁仓账户余额 5 定期存币生息账户余额 6 活期存币生息账户余额
 * @param float            $change           添加传正数，减少传负数
 * @param integer          $account_log_type 类似于之前的场景
 * @param string           $memo             备注
 * @param boolean          $is_lock          是否是冻结或解冻资金
 * @param integer          $from_user_id     触发用户id
 * @param integer          $extra_sign       子场景标识
 * @param string           $extra_data       附加数据
 * @param bool             $zero_continue    改变为0时继续执行,默认为假不执行
 * @param bool             $overflow         余额不足时允许继续处理,默认为假不允许
 *
 * @return true|string 成功返回真，失败返回假
 */
function change_wallet_balance(&$wallet, $balance_type, $change, $account_log_type, $memo = '', $is_lock = false, $from_user_id = 0, $extra_sign = 0, $extra_data = '', $zero_continue = false, $overflow = false)
{
    //为0直接返回真不往下再处理
    if (!$zero_continue && bc_comp($change, '0') == 0) {
        $path = base_path() . '/storage/logs/wallet/';
        $filename = date('Ymd') . '.log';
        file_exists($path) || @mkdir($path);
        error_log(date('Y-m-d H:i:s') . ' 改变金额为0,不处理' . PHP_EOL, 3, $path . $filename);
        return true;
    }

    $param = compact('balance_type', 'change', 'account_log_type', 'memo', 'is_lock', 'from_user_id', 'extra_sign', 'extra_data', 'zero_continue', 'overflow');
    try {
        if (!in_array($balance_type, [1, 2, 3,4, 5, 6])) {
            throw new \Exception('货币类型不正确');
        }
        DB::transaction(function () use (&$wallet, $param) {
            extract($param);
            $fields = [
                '',
                'legal_balance',
                'change_balance',
                'lever_balance',
                'locked_balance',
                'fixed_interest_balance',
                'current_interest_balance',
            ];
            $field = ($is_lock ? 'lock_' : '') . $fields[$balance_type];
            $wallet->refresh(); //取最新数据
            $user_id = $wallet->user_id;
            $before = $wallet->$field;
            $after = bc_add($before, $change);
            //判断余额是否充足
            if (bc_comp($after, '0') < 0 && !$overflow) {
                throw new \Exception('钱包' . $field . '余额不足');
            }
            $now = time();
            AccountLog::unguard();
            $account_log = AccountLog::create([
                'user_id' => $user_id,
                'value' => $change,
                'info' => $memo,
                'type' => $account_log_type,
                'created_time' => $now,
                'currency' => $wallet->currency,
            ]);
            WalletLog::unguard();
            $wallet_log = WalletLog::create([
                'account_log_id' => $account_log->id,
                'user_id' => $user_id,
                'from_user_id' => $from_user_id,
                'wallet_id' => $wallet->id,
                'balance_type' => $balance_type,
                'lock_type' => $is_lock ? 1 : 0,
                'before' => $before,
                'change' => $change,
                'after' => $after,
                'memo' => $memo,
                'extra_sign' => $extra_sign,
                'extra_data' => $extra_data,
                'create_time' => $now,
            ]);
            $wallet->$field = $after;
            $result = $wallet->save();
            if (!$result) {
                throw new \Exception('钱包变更余额异常');
            }
        });
        return true;
    } catch (\Exception $e) {
        return $e->getMessage();
    } finally {
        AccountLog::reguard();
        WalletLog::reguard();
    }
}

/**
 * 变更用户通证
 *
 * @param \App\Users $user             用户模型实例
 * @param float      $change           添加传正数，减少传负数
 * @param integer    $account_log_type 需在AccountLog中注册类型
 * @param string     $memo
 *
 * @return bool|string
 */
function change_user_candy(&$user, $change, $account_log_type, $memo)
{
    try {
        if (!$user) {
            throw new \Exception('用户异常');
        }
        $user->refresh();
        DB::beginTransaction();
        $before = $user->candy_number;
        $after = bc_add($before, $change);
        $user->candy_number = $after;
        $user_result = $user->save();
        if (!$user_result) {
            throw new \Exception('奖励通证到账失败');
        }
        $log_result = AccountLog::insertLog([
            'user_id' => $user->id,
            'value' => $change,
            'info' => $memo . ',原数量:' . $before . ',变更后:' . $after,
            'type' => $account_log_type,
        ]);
        if (!$log_result) {
            throw new \Exception('记录日志失败');
        }
        DB::commit();
        return true;
    } catch (\Exception $e) {
        DB::rollBack();
        return $e->getMessage();
    }
}

function make_multi_array($fields, $count, $datas)
{
    $return_array = [];
    for ($i = 1; $i <= $count; $i++) {
        $current_data = [];
        foreach ($fields as $key => $field) {
            $current_data[$field] = current($datas[$field]);
            next($datas[$field]);
        }
        $return_array[] = $current_data;
    }
    return $return_array;
}

function is_json($data = '', $assoc = false)
{
    $data = json_decode($data, $assoc);
    if ($data && (is_object($data)) || (is_array($data) && !empty(current($data)))) {
        return true;
    }
    return false;
}
