<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/6/1
 * Time: 13:46
 * 调整时间为税款所属期起为：2020-03-01至2020-12-01
 */
return [
    'person' => [
        'rate' => [//增值附加税表
            '1' => 0.07,//城市维护建设税
            '2' => 0.03,//教育费附加
            '3' => 0.02,//地方教育附加
            //            '4' => 0.01,//印花税
        ],
        'statistics' => \LazyBench\Tax\Statistics\UserMonth::class,
        'taxExtReduceRate' => 0.5,//增值附加税减免比
        'basisTax' => 10000000,//月税基数
        'basisTaxYear' => 120000000,//年税基数
        'baseRate' => 1.01,
        'addedTaxRate' => 0.01,
        'rowMap' => [
            5 => [-3000000, 3000000],
            10 => [3000000, 9000000],
            20 => [9000000, 30000000],
            30 => [30000000, 50000000],
            35 => [50000000, 100000000000],
        ],
        'rateMap' => [
            5 => 0,
            10 => 150000,
            20 => 1050000,
            30 => 4050000,
            35 => 6550000,
        ],
    ],
    'company' => [
        'rate' => [
            '1' => 0.07,
            '2' => 0.03,
            '3' => 0.02,
            //            '4' => 0.01,//印花税
        ],
        'taxExtReduceRate' => 0,//增值附加税减免比
        'baseRate' => 1.01,
        'addedTaxRate' => 0.01,
    ],
];