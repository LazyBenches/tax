<?php

namespace LazyBench\Tax\Traits;

use LazyBench\Tax\Constant\Tax;

/**
 * Author:LazyBench
 * Date:2019/1/8
 */
trait Calculate
{
    use SubTrait;

    protected static $basisTax = 100000;//月税基数
    protected static $basisTaxYear = 1200000;//年税基数

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
     * Author:LazyBench
     * 自增附加税率表
     * @var array
     */
    protected $rates;
    /**
     * Author:LazyBench
     * 增值附加税减免比
     * @var
     */
    protected $taxExtReduceRate = 0;

    /**
     * Author:LazyBench
     *
     * @param array $rates
     * @return $this
     */
    public function setRates(array $rates)
    {
        $this->rates = $rates;
        return $this;
    }

    public function setTaxExtReduceRate(string $rate)
    {
        $this->taxExtReduceRate = $rate;
        return $this;
    }

    /**
     * 计税依据
     * Author:LazyBench
     * @param $amount
     * @return string
     * 企业发放金额/1.03
     */
    public function setTaxBasis($amount): string
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
     * @return string
     * 计税依据（0.0002,0.0003,0.0007）
     */
    public function getBasisRateTax($total): string
    {
        $this->addTaxExtDetail = [];
        $tax = 0;
        foreach ($this->rates as $key => $rate) {
            $this->addTaxExtDetail[$key] = bcmul($total, $rate, Tax::SCALE);
            $tax = bcadd($tax, $this->addTaxExtDetail[$key], Tax::SCALE);
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
        foreach ($this->addTaxExtDetail as $key => $value) {
            $this->addTaxExtReduceDetail[$key] = bcmul($value, $this->taxExtReduceRate, Tax::SCALE);//Tax::TAX_EXT_REDUCE
            $tax = bcadd($tax, $this->addTaxExtReduceDetail[$key], Tax::SCALE);
        }
        return $tax;
    }


    public function setBasisTax($basisTax)
    {
        self::$basisTax = $basisTax;
    }

    public function setBasisTaxYear($basisTaxYear)
    {
        self::$basisTaxYear = $basisTaxYear;
    }

    public static function getBasisTax()
    {
        return self::$basisTax;
    }

    public static function getBasisTaxYear()
    {
        return self::$basisTaxYear;
    }
}
