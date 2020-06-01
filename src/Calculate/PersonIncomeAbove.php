<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/28
 * Time: 16:11
 */

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Logic\TaxCalculate;
use LazyBench\Tax\Log\PersonLog;
use LazyBench\Tax\Traits\Calculate;

class PersonIncomeAbove
{
    use Calculate;
    /**
     * Author:LazyBench
     *
     * @var PersonLog
     */
    protected $log;

    /**
     * PersonIncome constructor.
     * @param PersonLog $log
     */
    public function __construct(PersonLog $log)
    {
        $this->log = $log;
    }

    public function handle($personWages)
    {
        $log = TaxCalculateLogic::getPersonData($personWages, $this->log->idCard, $this->log->month);
        $log->personIncomeLeft = $this->floor($log->personIncomeLeft, 3);
        $compare = bcsub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
        if (!$this->floor($compare, 3)) {
            return $log;
        }
        $method = $compare < 0 ? 'bcsub' : 'bcadd';
        $i = 0;
        if ($compare !== 0) {
            while (true) {
                $i++;
                if ($i > 100) {
                    break;
                }
                $diff = $compare / 2;
                $personWages = $method($personWages, $diff, Tax::SCALE);
                $log = TaxCalculateLogic::getPersonData($personWages, $this->log->idCard, $this->log->month);
                $log->personIncomeLeft = $this->floor($log->personIncomeLeft, 3);
                $compare = bcSub($log->personIncomeLeft, $this->log->personIncomeLeft, Tax::SCALE);
                if ($compare > 0 && bccomp($compare / 2, $diff, Tax::SCALE) === 1) {
                    $compare = bcSub($this->log->personIncomeLeft, $log->personIncomeLeft, Tax::SCALE);
                }
                if (!$this->floor($compare, 3)) {
                    break;
                }
            }
        }
        return $log;
    }
}
