<?php

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Traits\Calculate;

/**
 * Author:LazyBench
 * Date:2019/1/8
 * 个人
 */
class Personal
{
    use Calculate;
    const RATES = [
        0.07,
        0.03,
        0.02,
    ];
    const BASIS_TAX = 100000;//月税基数
    const BASIS_TAX_YEAR = self::BASIS_TAX * 12;//年税基数
    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 当前税前
     */
    protected $personalWages;

    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 是否应当缴纳增值附加税
     */
    protected $isAddExt = false;
    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 历史税前
     */
    protected $personalWagesHistory;

    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 本月累计税前
     */
    protected $personWagesTotalMonth;

    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 个人实际缴纳综合赋税
     */
    protected $personTaxTotalReal;

    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 个人当年税基
     */
    protected $taxBaseYearTotal;
    /**
     * Author:LazyBench
     * Date:2019/1/15
     * @var
     * 设置上月个人所得税
     */
    protected $lastPersonTax;

    /**
     * Author:LazyBench
     * @param $wages
     * 当前税前
     */
    public function setPersonalWages($wages)
    {
        $this->personalWages = $wages;
    }

    /**
     * Author:LazyBench
     * @return mixed
     * 获取当前税前
     */
    public function getPersonWages()
    {
        return $this->personalWages;
    }

    /**
     * Author:LazyBench
     * @param $isAddExt
     * @return bool
     * 设置是否应当缴纳增值附加税
     */
    public function setIsAddExt($isAddExt)
    {
        $this->isAddExt = $isAddExt;
        return $this->isAddExt;
    }

    /**
     * Author:LazyBench
     * @param $historyWages
     * 历史税前
     */
    public function setPersonalWagesHistory($historyWages)
    {
        $this->personalWagesHistory = $historyWages;
        return $this->personalWagesHistory;
    }

    /**
     * Author:LazyBench
     * @return mixed
     * 获取历史税前
     */
    public function getPersonalWagesHistory()
    {
        return $this->personalWagesHistory;
    }

    /**
     * Author:LazyBench
     * @param $wages
     * @return string
     * 个人增值税
     */
    public function getTaxAddValue($wages)
    {
        return bcmul($this->getTaxBasis($wages), 0.03);
    }

    /**
     * Author:LazyBench
     * @param $addValueReal //实缴增值
     * @return int|string
     * 个人实缴增值税
     */
    public function getTaxAddValueReal($addValueReal)
    {
        if (!$this->isAddExt) {
            return 0.00;
        }
        $wagesTotal = $this->getMonthPersonWagesTotal();//个人本月累计税前;
        //当月总共增值税-当月历史实际已缴纳增值税
        $total = $this->getTaxAddValue($wagesTotal);
        return bcsub($total, $addValueReal);
    }

    /**
     * Author:LazyBench
     * @param $addValueReduction
     * @return string
     * 本次减免增值
     */
    public function getTaxAddValueReduction($addValueReduction)
    {
        if (!$this->isAddExt) {//减免
            $wagesTotal = $this->getMonthPersonWagesTotal();//个人本月累计税前
            $total = $this->getTaxAddValue($wagesTotal);
            return bcsub($total, $addValueReduction);
        }
        return $addValueReduction > 0 ? -$addValueReduction : 0.00;
    }

    /**
     * Author:LazyBench
     * 设置当年税基
     * @param $taxBaseYearTotal
     */
    public function setTaxBaseYear($taxBaseYearTotal)
    {
        $this->taxBaseYearTotal = $taxBaseYearTotal;
        return $this->taxBaseYearTotal;
    }

    /**
     * Author:LazyBench
     * 设置上月个人所得税
     * @param $lastPersonTax
     */
    public function setLastPersonTax($lastPersonTax)
    {
        $this->lastPersonTax = $lastPersonTax;
        return $this->lastPersonTax;
    }

    /**
     * Author:LazyBench
     * @param $wages
     * @return mixed
     * 个人增值附加税
     */
    public function getTaxAddExt($wages)
    {
        $total = $this->getTaxAddValue($wages);
        $return = $this->getBasisRateTax($total);
        return $return['total'];
    }

    /**
     * Author:LazyBench
     * @param $taxAddExtReal //累计增值附加
     * @return string
     */
    public function getTaxAddExtReal($taxAddExtReal)
    {
        if (!$this->isAddExt) {
            return 0.00;
        }
        $wagesTotal = $this->getMonthPersonWagesTotal();//个人本月累计税前;
        $total = $this->getTaxAddExt($wagesTotal);
        return bcsub($total, $taxAddExtReal);
    }

    /**
     * Author:LazyBench
     * @param $taxAddExtReduction
     * @return float|int|string
     * 增值附加减免（本次）
     */
    public function getTaxAddExtReduction($taxAddExtReduction)
    {
        if (!$this->isAddExt) {
            $wagesTotal = $this->getMonthPersonWagesTotal();//个人本月累计税前
            $total = $this->getTaxAddExt($wagesTotal);
            return bcsub($total, $taxAddExtReduction);
        }
        return $taxAddExtReduction > 0 ? -$taxAddExtReduction : 0.00;
    }

    /**
     * Author:LazyBench
     * @param $wages
     * @return string
     * 个人增值附加税
     */
    public function getTaxAddValueExt($wages)
    {
        return bcadd($this->getTaxAddValue($wages), $this->getTaxAddExt($wages));
    }

    /**
     * Author:LazyBench
     * @param $wages
     * @return string
     * 个人所得税
     */
    public function getPersonTax($wages)
    {
        //        $total = $this->getTaxReduction($wages);//扣除计算数
        $taxTotal = $this->getPersonalTaxByCompany($this->taxBaseYearTotal);
        return bcsub($taxTotal, $this->lastPersonTax);
    }

    /**
     * Author:LazyBench
     * @param $personTaxTotal
     * @return string
     */
    public function getPersonTaxReal($personTaxTotal)
    {
        $wagesTotal = $this->getMonthPersonWagesTotal();//个人本月累计税前
        $tax = $this->getPersonTax($wagesTotal);//本月应收个税总额
        return bcsub($tax, $personTaxTotal);
    }

    /**
     * Author:LazyBench
     * @param $money
     * @return string
     * 扣除计算数
     */
    public function getTaxReduction($money)
    {
        if ($this->isAddExt) {
            $money = bcsub($money, $this->getTaxAddValue($money));
        }
        return bcmul($money, Tax::RATE_DISCOUNT);
    }

    /**
     * Author:LazyBench
     *
     * @param $total
     * @param array $getPersonalTaxRateSettings
     * @return int|string
     */
    protected function getPersonalTaxByCompany($total, array $getPersonalTaxRateSettings)
    {
        $maxLevel = count($getPersonalTaxRateSettings);
        $taxTotal = 0;
        foreach ($getPersonalTaxRateSettings as $key => $getPersonalTaxRateSetting) {
            $currentLevel = $key + 1;
            if ($currentLevel === $maxLevel) {
                $baseTaxTotal = $total;
            } else {
                $baseTaxTotal = bcsub($getPersonalTaxRateSetting['to'], $getPersonalTaxRateSetting['from']);
            }
            $currentTax = bcmul($baseTaxTotal, $getPersonalTaxRateSetting['rate']);
            $total = bcsub($total, $baseTaxTotal);
            $taxTotal = bcadd($taxTotal, $currentTax);
        }
        return $taxTotal;
    }

    /**
     * Author:LazyBench
     * @param $wages //个人税前
     * @return string
     * 个人综合赋税
     */
    public function getPersonTaxTotal($wages)
    {
        $personTax = $this->getPersonTax($wages);//个人所得税
        $taxAddValueExt = $this->getTaxAddValueExt($wages);//个人增值附加总和
        return bcadd($personTax, $taxAddValueExt);
    }

    /**
     * Author:LazyBench
     * @param $history
     * @return string
     * 当综合赋税
     */
    public function getPersonTaxTotalNow($history)
    {
        $wages = $this->getMonthPersonWagesTotal();//个人本月累计税前;
        $total = $this->getPersonTaxTotal($wages);
        return bcsub($total, $history);
    }

    /**
     * Author:LazyBench
     * @param $historyTaxTotal
     * @return string
     * 当综合赋税(实际)
     */
    public function getPersonTaxTotalReal($historyTaxTotal)
    {
        if (!$this->personTaxTotalReal) {
            $wages = $this->getMonthPersonWagesTotal();//个人本月累计税前
            //总计当月综合
            $taxTotal = $this->getPersonTax($wages);//个人所得税
            if ($this->isAddExt) {
                $taxAddValueExt = $this->getTaxAddValueExt($wages);//个人增值附加总和
                $taxTotal = bcadd($taxTotal, $taxAddValueExt);
            }
            $this->personTaxTotalReal = bcsub($taxTotal, $historyTaxTotal);//应缴-实缴
        }
        return $this->personTaxTotalReal;
    }

    /**
     * Author:LazyBench
     * @param int $historyTaxTotal //历史实际缴纳综合
     * @return string
     * 个人实际收入（本次）
     */
    public function getPersonIncome($historyTaxTotal = 0)
    {
        $sub = $this->getPersonTaxTotalReal($historyTaxTotal);
        return bcsub($this->getPersonWages(), $sub);
    }

    /**
     * Author:LazyBench
     * @return float
     * 个人印花
     */
    public function getPersonalStampTax()
    {
        return 0.00;
    }

    /**
     * Author:LazyBench
     * @return string
     * 获取本月累计税前（包括当前）
     */
    public function getMonthPersonWagesTotal()
    {
        if (!$this->personWagesTotalMonth) {
            $historyWages = $this->getPersonalWagesHistory();
            $nowWages = $this->getPersonWages();
            $this->personWagesTotalMonth = bcadd($nowWages, $historyWages);
        }
        return $this->personWagesTotalMonth;
    }
}
