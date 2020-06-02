<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/6/1
 * Time: 13:46
 */
return [
    'person' => [
        'rate' => [
            '1' => 0.07,
            '2' => 0.03,
            '3' => 0.02,
        ],
        'statistics' => \LazyBench\Tax\Statistics\UserMonth::class,
    ],
    'company' => [
        'rate' => [
            '1' => 0.07,
            '2' => 0.03,
            '3' => 0.02,
        ],
    ],
];