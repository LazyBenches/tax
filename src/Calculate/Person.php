<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/25
 * Time: 18:59
 */

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Log\PersonLog;
use LazyBench\Tax\Traits\Calculate;

class Person
{
    use Calculate;

    /**
     * Author:LazyBench
     * 附加税减免
     * @var
     */
    protected $reduceAddTaxExt;

    /**
     * Author:LazyBench
     * 月税前
     * @var
     */
    protected $personWages;

    /**
     * Author:LazyBench
     * 累计1到上个月税基
     * @var
     */
    protected $taxBaseYearLastMonthTotal;

    /**
     * Author:LazyBench
     * 累计1到上个月的个税
     * @var
     */
    protected $personTaxLastTotal;
    /**
     * Author:LazyBench
     * 当月总增值税
     * @var
     */
    protected $addTax;

    /**
     * Author:LazyBench
     * 当月总增值附加税
     * @var
     */
    protected $addTaxExt;

    /**
     * Author:LazyBench
     * 个人所得税
     * @var
     */
    protected $personTax;

    /**
     * Author:LazyBench
     * 累计1到当前月的个人所得税
     * @var
     */
    protected $personTaxTotal;
    /**
     * Author:LazyBench
     * 本月税基
     * @var
     */
    protected $taxBase;

    /**
     * Author:LazyBench
     * 是否缴纳增值附加
     * @var bool
     */
    protected $isAdd = false;

    /**
     * Author:LazyBench
     * 个人所得税率档税率
     * @var string
     */
    protected $personTaxRate;

    /**
     * Author:LazyBench
     * 个人所得税率档
     * @var
     */
    protected $personTaxRateKey = 0;

    /**
     * Author:LazyBench
     * 个人所得税扣除
     * @var string
     */
    protected $personTaxRateReduce;

    /**
     * Author:LazyBench
     *
     * @var PersonLog|null
     */
    protected $log;

    /**
     * Person constructor.
     * @param PersonLog $log
     */
    public function __construct(PersonLog $log)
    {
        $this->log = $log;
        $this->setIsAdd($this->log->isAdd);
        $this->setPersonWages($this->log->personWages);
        $this->setTaxBaseYearLastMonthTotal($this->log->taxBaseYearLastMonthTotal);
        $this->setPersonTaxLastTotal($this->log->personTaxLastTotal);
    }

    /**
     * Author:LazyBench
     *
     * @param $taxRateTable
     * @return PersonLog
     */
    public function handle($taxRateTable): PersonLog
    {
        $this->getAddTax();//初始化增值税
        $this->getAddTaxExt();//初始化增值税附加
        $this->getPersonTax($taxRateTable);
        $this->log->isAdd = $this->isAdd;
        $this->log->personWages = $this->personWages;
        $this->log->personWagesLeft = bcsub($this->personWages, $this->log->monthTotal['taxWages'] ?? 0, Tax::SCALE);
        $this->log->taxBaseYearLastMonthTotal = $this->taxBaseYearLastMonthTotal;
        $this->log->personTaxTotal = $this->personTaxTotal;
        $this->log->personTaxLastTotal = $this->personTaxLastTotal;
        $this->log->addTaxExt = bcadd($this->getAddTaxExt(), $this->getAddTaxExtReduction(), Tax::SCALE);
        $this->log->addTax = bcadd($this->getAddTax(), $this->getAddTaxReduction(), Tax::SCALE);
        $this->log->personTax = $this->personTax;
        $this->log->taxBase = $this->taxBase;
        $this->log->personTaxAmount = bcadd($this->log->personTax, bcadd($this->log->addTax, $this->log->addTaxExt, Tax::SCALE), Tax::SCALE);
        $this->log->personIncome = bcsub($this->log->personWages, $this->log->personTaxAmount, Tax::SCALE);
        //增值和附加 本次，当月，当月已缴纳
        $addTaxAlready = $this->log->monthTotal['personAddTax'] ?? 0;
        $this->log->addTaxLeft = bcsub($this->log->addTax, $addTaxAlready, Tax::SCALE);
        $addTaxExtAlready = $this->log->monthTotal['personAddTaxExt'] ?? 0;
        $this->log->addTaxExtLeft = bcsub($this->log->addTaxExt, $addTaxExtAlready, Tax::SCALE);
        $personTaxAlready = $this->log->monthTotal['personTax'] ?? 0;
        $this->log->personTaxLeft = bcsub($this->log->personTax, $personTaxAlready, Tax::SCALE);
        $personTaxAmountAlready = $this->log->monthTotal['personTaxAmount'] ?? 0;
        $this->log->personTaxAmountLeft = bcsub($this->log->personTaxAmount, $personTaxAmountAlready, Tax::SCALE);
        $personIncomeAlready = $this->log->monthTotal['personIncome'] ?? 0;
        $this->log->personIncomeLeft = bcsub($this->log->personIncome, $personIncomeAlready, Tax::SCALE);
        //增值减免
        $this->log->addTaxReduction = ltrim($this->getAddTaxReduction(), '-');//当月减免
        $addTaxReductionAlready = $this->log->monthTotal['personAddTaxIng'] ?? 0;//当月累计已减免
        $this->log->addTaxReductionLeft = bcsub($this->log->addTaxReduction, $addTaxReductionAlready, Tax::SCALE);
        //附加减免
        $this->log->addTaxExtReduction = ltrim($this->getAddTaxExtReduction(), '-');//当月减免
        $addTaxReductionExtAlready = $this->log->monthTotal['personAddTaxExtIng'] ?? 0;//当月累计已减免
        $this->log->addTaxExtReductionLeft = bcsub($this->log->addTaxExtReduction, $addTaxReductionExtAlready, Tax::SCALE);
        $this->log->taxAmountShould = bcsub($this->log->personTaxAmount, $this->log->monthTotal['personTaxAmountShould'] ?? 0, Tax::SCALE);
        return $this->log;
    }

    /**
     * Author:LazyBench
     * 设置个人税前
     * @param $personWages
     * @return $this
     */
    protected function setPersonWages($personWages)
    {
        $this->personWages = $personWages;
        return $this;
    }


    /**
     * Author:LazyBench
     * 设置1到上个月税基
     * @param $taxBaseYearLastMonthTotal
     */
    protected function setTaxBaseYearLastMonthTotal($taxBaseYearLastMonthTotal)
    {
        $this->taxBaseYearLastMonthTotal = $taxBaseYearLastMonthTotal;
    }

    /**
     * Author:LazyBench
     * 累计1到上月的个人所得税
     * @param $personTaxLastTotal
     */
    protected function setPersonTaxLastTotal($personTaxLastTotal)
    {
        $this->personTaxLastTotal = $personTaxLastTotal;
    }

    /**
     * Author:LazyBench
     * @param $personWages
     * @return string
     * 个税税基
     */
    protected function getTaxBase($personWages)
    {
        $money = bcsub($personWages, $this->getAddTax(), Tax::SCALE);
        $money = bcsub($money, $this->getAddTaxReduction(), Tax::SCALE);
        return bcmul($money, Tax::RATE_DISCOUNT, Tax::SCALE);
    }

    /**
     * Author:LazyBench
     * 计算当月个税税基
     * @return string
     */
    protected function getTaxBaseMonth()
    {
        if ($this->taxBase) {
            return $this->taxBase;
        }
        $this->taxBase = $this->getTaxBase($this->personWages);
        return $this->taxBase;
    }

    /**
     * Author:LazyBench
     * 获取个人税前
     * @return mixed
     */
    protected function getPersonWages()
    {
        return $this->personWages;
    }

    /**
     * Author:LazyBench
     * @param $isAdd
     * @return Person
     * 设置是否应当缴纳增值附加税
     */
    protected function setIsAdd($isAdd)
    {
        $this->isAdd = $isAdd;
        return $this;
    }

    /**
     * Author:LazyBench
     * 获取是否缴纳增值附加税
     * @return bool
     */
    protected function getIsAdd()
    {
        return $this->isAdd;
    }

    /**
     * Author:LazyBench
     * @return string
     * 个人增值税
     */
    protected function getAddTax()
    {
        if ($this->addTax) {
            return $this->addTax;
        }
        $this->addTax = bcmul($this->getTaxBasis($this->personWages), self::getAddedTaxRate(), Tax::SCALE);
        return $this->addTax;
    }

    /**
     * Author:LazyBench
     * @return string
     * 个人增值税减免
     */
    protected function getAddTaxReduction()
    {
        //缴纳增值税，不减免
        if ($this->isAdd) {
            return 0;
        }
        return -$this->getAddTax();
    }


    /**
     * Author:LazyBench
     * @return mixed
     * 个人增值附加税
     */
    protected function getAddTaxExt()
    {
        if ($this->addTaxExt) {
            return $this->addTaxExt;
        }
        $taxAdd = $this->getAddTax();
        $this->addTaxExt = $this->getBasisRateTax($taxAdd);
        return $this->addTaxExt;
    }

    /**
     * Author:LazyBench
     * @return mixed
     * 个人增值附加税减免
     */
    protected function getAddTaxExtReduction()
    {
        if ($this->reduceAddTaxExt !== null) {
            return $this->reduceAddTaxExt;
        }
        //缴纳增值税，减免部分
        if ($this->isAdd) {
            $reduceAddTaxExt = $this->getReduceRateTax();
            $this->reduceAddTaxExt = -$reduceAddTaxExt;
            return $this->reduceAddTaxExt;
        }
        $this->reduceAddTaxExt = -$this->getAddTaxExt();
        return $this->reduceAddTaxExt;
    }


    /**
     * Author:LazyBench
     * @param $taxRateTable array
     * @param $key
     * @return string
     * 个人所得税
     */
    protected function getPersonTax($taxRateTable, $key = 0)
    {
        if ($this->personTax) {
            return $this->personTax;
        }
        $this->setPersonTaxKey($key);
        $total = bcadd($this->taxBaseYearLastMonthTotal, $this->getTaxBaseMonth(), Tax::SCALE);
        $this->personTaxTotal = $this->getPersonalTaxTotal($total, $taxRateTable);
        $this->personTax = bcsub($this->personTaxTotal, $this->personTaxLastTotal, Tax::SCALE);
        return $this->personTax;
    }


    //    /**
    //     * Author:LazyBench
    //     * 个人所得税税率计算个人所得税
    //     * @param  $total
    //     * @param  $taxRateTable
    //     * @return int|string
    //     */
    //    protected function getPersonalTaxTotal($total, $taxRateTable)
    //    {
    //        $taxTotal = 0;
    //        $compare = $total;
    //        foreach ($taxRateTable as $key => $taxRate) {
    //            if ($taxRate['from'] > $compare) {
    //                break;
    //            }
    //            if ($taxRate['from'] < $compare) {
    //                $baseTaxTotal = $total;
    //            } else {
    //                $baseTaxTotal = bcsub($taxRate['to'], $taxRate['from'], Tax::SCALE);
    //            }
    //            //            1)	应纳税所得额=收入总额×应税所得率
    //            $currentTax = bcmul($baseTaxTotal, $taxRate['rate'], Tax::SCALE);
    //            //            2)	应纳所得税额=应纳税所得额×适应税率-速算扣除数
    //            $currentTax -= $taxRate['reduce'];
    //            $total = bcsub($total, $baseTaxTotal, Tax::SCALE);
    //            $taxTotal = bcadd($taxTotal, $currentTax, Tax::SCALE);
    //        }
    //        return $taxTotal;
    //    }

    /**
     * Author:LazyBench
     * 个人所得税税率计算个人所得税
     * @param  $total
     * @param  $taxRateTable
     * @return int|string
     */
    protected function getPersonalTaxTotal($total, $taxRateTable)
    {
        //        foreach ($taxRateTable as $key => $taxRate) {
        //            if ($total <= $taxRate['from']) {
        //                break;
        //            }
        //            if ($total > $taxRate['to']) {
        //                continue;
        //            }
        //            $this->personTaxRate = $taxRate['rate'];
        //            $this->personTaxRateReduce = $taxRate['reduce'];
        //        }

        $this->personTaxRate = $taxRateTable[$this->personTaxRateKey]['rate'];
        $this->personTaxRateReduce = $taxRateTable[$this->personTaxRateKey]['reduce'];
        return bcmul($total, $this->personTaxRate, Tax::SCALE) - $this->personTaxRateReduce;
    }

    /**
     * Author:LazyBench
     *
     * @param $total
     * @param array $taxRateTable
     * @return int|string
     */
    public function switchPersonTaxKey($total, array $taxRateTable)
    {
        foreach ($taxRateTable as $key => $taxRate) {
            if ($total <= $taxRate['from']) {
                break;
            }
            if ($total > $taxRate['to']) {
                continue;
            }
            if ($total >= $taxRate['from'] && $total < $taxRate) {
                return $key;
            }
        }
        return 0;
    }

    public function setPersonTaxKey($key)
    {
        $this->personTaxRateKey = $key;
    }
}
