<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/4/28
 * Time: 15:06
 */

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Log\PersonLog;
use LazyBench\Tax\Traits\Calculate;

class PersonIncomeBelow
{
    use Calculate;
    /**
     * Author:LazyBench
     *
     * @var PersonLog
     */
    protected $log;
    protected $rateBelow;

    /**
     * PersonIncome constructor.
     * @param PersonLog $log
     * @param PersonLog $belowLog
     */
    public function __construct(PersonLog $log, PersonLog $belowLog)
    {
        $this->log = $log;
        $this->rateBelow = $this->getPersonWagesBelowRate($belowLog);
    }

    /**
     * Author:LazyBench
     * 不缴纳增值附加比例
     * @param PersonLog $belowLog
     * @return string|null
     */
    protected function getPersonWagesBelowRate(PersonLog $belowLog)
    {
        return bcdiv($belowLog->personIncomeLeft, 1, Tax::SCALE);
    }
    //    /**
    //     * Author:LazyBench
    //     * 不缴纳增值附加比例
    //     * @return string|null
    //     */
    //    protected function getPersonWagesBelowRate($log)
    //    {
    //        $log = TaxCalculateLogic::getPersonData(1);
    //        return bcdiv($log->personIncomeLeft, 1, Tax::SCALE);
    //    }

    /**
     * Author:LazyBench
     * 回推不缴纳增值附加
     * @return string|null
     */
    protected function getPersonWagesBelowCalculate()
    {
        return bcdiv($this->log->personIncomeLeft, $this->rateBelow, Tax::SCALE);
    }

    /**
     * Author:LazyBench
     * 按比例算出来的personIncome 是收入(不纳增值附加)中最高的，也就是说，personWages 是最小的,个人所得税缴纳最少的
     * @param \LazyBench\Tax\Tax $tax
     * @param $scale
     * @return PersonLog
     */
    public function handle(\LazyBench\Tax\Tax $tax, $scale = Tax::SCALE)
    {
        $personWages = $this->getPersonWagesBelowCalculate();
        $data = $tax->getStatistics()->getStaticMonth($personWages, $this->log->idCard, $this->log->month);
        $log = $tax->getPersonData($personWages, $this->log->idCard, $this->log->month, $data);
        if ($log->isAdd) {
            return $log;
        }
        $compare = bcsub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
        if (!$this->floor($compare, 3)) {
            return $log;
        }
        $method = $compare < 0 ? 'bcsub' : 'bcadd';
        if (abs($compare)) {
            while (true) {
                $diff = $compare / 2;
                $personWages = $method($personWages, $diff, Tax::SCALE);
                $log = $tax->getPersonData($personWages, $this->log->idCard, $this->log->month, $data);
                $compare = bcSub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
                if ($compare > 0 && bccomp($compare / 2, $diff, $scale) === 1) {
                    $compare = bcSub($this->log->personIncomeLeft, $log->personIncomeLeft, Tax::SCALE);
                }
                if (!$this->floor($compare, $scale)) {
                    break;
                }
            }
        }
        return $log;
    }
}
