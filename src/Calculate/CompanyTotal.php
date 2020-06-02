<?php

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Traits\Calculate;

/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/3/15
 * Time: 16:13
 */
class CompanyTotal
{
    use Calculate;

    const COMPANY_RATE = [
        'addTax' => 0.06,
        'stampTax' => 0.0003,
    ];

    const RATES = [
        0.07,
        0.03,
        0.02,
    ];


    /**
     * Author:LazyBench
     * 企业发放金额
     * @param $amount
     * @param $poundageRate
     * @return string|null
     */
    public function personalWages($amount, $poundageRate): string
    {
        $addTaxExtRate = bcadd(array_sum($this->rates), 0, 3);
        //   企业实际支付 = 企业增值 C2+ 企业增值附加 C3 +  企业印花 C4 + 企业发放金额 C5
        $C5 = 1;//企业发放金额
        $C2 = self::COMPANY_RATE['addTax'];//企业增值 = C5*0.006
        $C3 = bcmul($addTaxExtRate, $C2, Tax::SCALE);//企业增值附加 = C2*0.12
        $C4 = bcmul(($C2 + $C5), self::COMPANY_RATE['stampTax'], Tax::SCALE);//企业印花 = (C2+C5)*0.0003
        $C1 = ($C2 + $C3 + $C4 + $C5);//
        $G3 = bcmul($poundageRate, $C1, Tax::SCALE);
        $total = $C1 + $G3;
        return bcdiv($amount, $total, Tax::SCALE);
    }
}
