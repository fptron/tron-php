<?php

namespace Tron\Support;

/**
 * 数据签名
 * Class Formatter
 * @package Ethereum
 */
class Formatter
{

    /**
     * 对于方法名和参数类型做签名
     * @param $method
     * @return string
     */
    public static function toMethodFormat($method)
    {
        return Utils::stripZero(substr(Utils::sha3($method), 0, 10));
    }

    /**
     * 地址签名
     * @param $address
     * @return string
     */
    public static function toAddressFormat($address)
    {
        $address = strtolower(trim($address));
        // 去掉 0x 前缀（EVM 风格）
        if (strpos($address, '0x') === 0) {
            $address = substr($address, 2);
        }
        // TRON hex address: 41 + 20 bytes
        if (preg_match('/^41[a-f0-9]{40}$/', $address)) {
            $address = substr($address, 2);
        }
        // 最终强校验：address 必须是 20 bytes
        if (!preg_match('/^[a-f0-9]{40}$/', $address)) {
            throw new \InvalidArgumentException(
                'Invalid TRC20 address format, expect 20-byte hex'
            );
        }
        // ABI address 左补 0 到 32 bytes
        return str_pad($address, 64, '0', STR_PAD_LEFT);
    }

    /**
     * 数字签名
     * @param $value
     * @param int $digit
     * @return string
     */
    public static function toIntegerFormat($value, $digit = 64)
    {
        $bn = Utils::toBn($value);
        $bnHex = $bn->toHex(true);

        if (mb_strlen($bnHex) > $digit) {
            throw new \InvalidArgumentException('Integer hex overflow');
        }
        return str_pad($bnHex, $digit, '0', STR_PAD_LEFT);
    }
}
