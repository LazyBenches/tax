<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/7/2
 * Time: 14:15
 */

namespace Application\Core\Components\Constants;

class Tax
{
    const RATE_DISCOUNT = 0.12;
    const TAX_EXT_REDUCE = 0.5;
    const SCALE = 8;
    const ROW_MAP = [
        5 => [-30000, 30000],
        10 => [30000, 90000],
        20 => [90000, 300000],
        30 => [300000, 500000],
        35 => [500000, 1000000000],
    ];

    const RATE_MAP = [
        '5' => 0,
        '10' => 1500,
        '20' => 10500,
        '30' => 40500,
        '35' => 65500,
    ];
}
