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
        'rate' => [//增值附加税表
            '1' => 0.07,
            '2' => 0.03,
            '3' => 0.02,
        ],
        'statistics' => \LazyBench\Tax\Statistics\UserMonth::class,
        'taxExtReduceRate' => 0.5,//增值附加税减免比
        'basisTax' => 100000,//月税基数
        'basisTaxYear' => 1200000//年税基数
    ],
    'company' => [
        'rate' => [
            '1' => 0.07,
            '2' => 0.03,
            '3' => 0.02,
        ],
        'taxExtReduceRate' => 0,//增值附加税减免比
    ],
];