<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/7/2
 * Time: 14:20
 */

namespace LazyBench\Tax\Statistics;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Calculate\Person;
use LazyBench\Tax\Traits\SubTrait;

class User
{
    use  SubTrait;

    /**
     * Author:LazyBench
     * 是否缴纳增值附加
     * @param $basis
     * @param $taxBasis
     * @return int
     */
    public function isAddTax($basis, $taxBasis = 0): int
    {
        $isAddTax = ($basis > Person::getBasisTax()) ?: false;//是否缴纳增值附加
        if ($isAddTax) {
            return 1;
        }
        if (bcadd($taxBasis, $basis, 2) > Person::getBasisTaxYear()) {
            return 1;
        }
        return 0;
    }


    /**
     * Author:LazyBench
     * 应纳税额统计
     * @param $incomeTotal
     * @param $rate
     * @return string
     */
    public function getTaxAmountTotal($incomeTotal, $rate): string
    {
        $taxBasis = bcmul($incomeTotal, Tax::RATE_DISCOUNT, Tax::SCALE);
        return bcsub(bcmul($taxBasis, $rate / 100, Tax::SCALE), Tax::RATE_MAP[$rate], Tax::SCALE);
    }


    /**
     * Author:LazyBench
     * 税率
     * @param $amount
     * @return int|string
     */
    public function switchRate($amount)
    {
        foreach (Tax::ROW_MAP as $rate => $value) {
            if ($amount > $value[0] && $amount <= $value[1]) {
                return $rate;
            }
        }
    }
}
