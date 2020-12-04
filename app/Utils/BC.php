<?php


namespace App\Utils;


class BC
{
    /**
     * @param string $num1
     * @param string $symbol
     * @param string $num2
     * @param int    $decimals
     *
     * @return bool|string|null
     */
    public static function compute($num1, $symbol, $num2, $decimals = DECIMAL_SCALE)
    {
        bcscale($decimals);
        $num1 = self::setFormatDecimal($num1, $decimals);
        $num2 = self::setFormatDecimal($num2, $decimals);
        switch ($symbol) {
            case '+':
                return bcadd($num1, $num2);
                break;
            case '-':
                return bcsub($num1, $num2);
                break;
            case '*':
                return bcmul($num1, $num2);
                break;
            case '/':
                return bcdiv($num1, $num2);
                break;
            case '>':
                return bccomp($num1, $num2) > 0;
                break;
            case '<':
                return bccomp($num1, $num2) < 0;
                break;
            case '>=':
                return bccomp($num1, $num2) >= 0;
                break;
            case '<=':
                return bccomp($num1, $num2) <= 0;
                break;
            case '^':
                return bcpow($num1, $num2);
                break;
        }
    }

    private static function sctonum($num, $double = DECIMAL_SCALE)
    {
        if (false !== stripos($num, "e")) {
            $a = explode("e", strtolower($num));
            return bcmul(strval($a[0]), bcpow('10', $a[1], $double), $double);
        } else {
            return strval($num);
        }
    }

    private static function setFormatDecimal($number, $decimal_scale)
    {
        $number = self::sctonum($number, $decimal_scale);
        $suffix = str_repeat('0', $decimal_scale);
        $dot_num = substr_count($number, '.');

        // 防止小数位数参数不是整数
        $decimal_scale = intval($decimal_scale);

        $decimal = '';
        if ($dot_num == 0) {
            $integer = $number;
        } else {
            list($integer, $decimal) = explode('.', $number, 2);
        }
        $decimal = substr($decimal . $suffix, 0, $decimal_scale);
        return $integer . ($decimal_scale == 0 ? '' : '.') . $decimal;
    }
}