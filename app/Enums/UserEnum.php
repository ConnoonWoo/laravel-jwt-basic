<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserEnum extends Enum
{
    // 状态类别
    const INVALID = -1; //已删除
    const NORMAL = 0; //正常
    const FREEZE = 1; //冻结

    public static function getStatusName($status){
        switch ($status){
            case self::INVALID:
                return '已删除';
            case self::FREEZE:
                return '冻结';
            default:
                return '正常';
        }
    }
}
