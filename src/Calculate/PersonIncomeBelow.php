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
use LazyBench\Logic\TaxCalculateLogic;
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
     */
    public function __construct(PersonLog $log)
    {
        $this->log = $log;
        $this->rateBelow = $this->getPersonWagesBelowRate();
    }

    /**
     * Author:LazyBench
     * 不缴纳增值附加比例
     * @param int $wages
     * @return string|null
     */
    protected function getPersonWagesBelowRate()
    {
        $log = TaxCalculateLogic::getPersonData(1);
        return bcdiv($log->personIncomeLeft, 1, Tax::SCALE);
    }

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
     * @return PersonLog
     */
    public function handle()
    {
        $personWages = $this->getPersonWagesBelowCalculate();
        $log = TaxCalculateLogic::getPersonData($personWages, $this->log->idCard, $this->log->month);
        if ($log->isAdd) {
            return $log;
        }
        $compare = bcsub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
        if ($this->floor($compare, 3) == 0) {
            return $log;
        }
        $method = $compare < 0 ? 'bcsub' : 'bcadd';
        if ($compare != 0) {
            while (true) {
                $diff = $compare / 2;
                $personWages = $method($personWages, $diff, Tax::SCALE);
                $log = TaxCalculateLogic::getPersonData($personWages, $this->log->idCard, $this->log->month);
                $compare = bcSub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
                if ($compare > 0 && bccomp($compare / 2, $diff, Tax::SCALE) === 1) {
                    $compare = bcSub($this->log->personIncomeLeft, $log->personIncomeLeft, Tax::SCALE);
                }
                if ($this->floor($compare, 3) == 0) {
                    break;
                }
            }
        }
        return $log;
    }
}
