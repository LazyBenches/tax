<?php

namespace LazyBench\Tax\Traits;

use LazyBench\Constants\Tax;

/**
 * Author:LazyBench
 * Date:2019/1/8
 */
trait CalculateTrait
{
    use SubTrait;
    /**
     * 计税依据
     * Author:LazyBench
     * Date:2019/1/8
     * @var
     */
    protected $taxBasis;
    /**
     * Author:LazyBench
     * 附加税细节
     * @var array
     */
    protected $addTaxExtDetail = [];
    /**
     * Author:LazyBench
     * 附加税减免细节
     * @var array
     */
    protected $addTaxExtReduceDetail = [];


    /**
     * 计税依据
     * Author:LazyBench
     * @param $amount
     * @return string
     * 企业发放金额/1.03
     */
    public function setTaxBasis($amount)
    {
        if (!$this->taxBasis) {
            $this->taxBasis = bcdiv($amount, 1.03, Tax::SCALE);
        }
        return $this->taxBasis;
    }

    /**
     * Author:LazyBench
     * @param $amount
     * @return mixed
     * 获取计税依据
     */
    public function getTaxBasis($amount = 0)
    {
        if ($amount) {
            return bcdiv($amount, 1.03, Tax::SCALE);
        }
        return $this->taxBasis;
    }

    /**
     * Author:LazyBench
     * @param $total
     * @return array
     * 计税依据（0.0002,0.0003,0.0007）
     */
    public function getBasisRateTax($total)
    {
        $this->addTaxExtDetail = [];
        $tax = 0;
        foreach (self::RATES as $rate) {
            $this->addTaxExtDetail["{$rate}"] = bcmul($total, $rate, Tax::SCALE);
            $tax = bcadd($tax, $this->addTaxExtDetail["{$rate}"], Tax::SCALE);
        }
        return $tax;
    }

    /**
     * Author:LazyBench
     * 附加税减免
     * @return int|string
     */
    public function getReduceRateTax()
    {
        $this->addTaxExtReduceDetail = [];
        $tax = 0;
        foreach ($this->addTaxExtDetail as $rate => $value) {
            $this->addTaxExtReduceDetail["{$rate}"] = bcmul($value, Tax::TAX_EXT_REDUCE, Tax::SCALE);
            $tax = bcadd($tax, $this->addTaxExtReduceDetail["{$rate}"], Tax::SCALE);
        }
        return $tax;
    }
}
