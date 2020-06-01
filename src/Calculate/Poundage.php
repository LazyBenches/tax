<?php

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Log\PoundageLog;
use LazyBench\Tax\Traits\Calculate;

/**
 * Author:LazyBench
 * Date:2019/1/9
 */
class Poundage
{
    use Calculate;
    //平台手续费

    protected $poundage;

    protected $baseAmount;

    /**
     * Author:LazyBench
     *
     * @var PoundageLog
     */
    protected $log;

    /**
     * Poundage constructor.
     * @param PoundageLog $log
     */
    public function __construct(PoundageLog $log)
    {
        $this->log = $log;
    }

    public function handle()
    {
        $this->baseAmount = $this->log->poundageBase;
        $this->log->poundage = $this->getPoundage($this->log->rate);
    }

    /**
     * 平台手续费
     * Author:Robert
     *
     * @param $rate
     * @return string
     */
    protected function getPoundage($rate):string
    {
        if ($this->poundage) {
            return $this->poundage;
        }
        $this->poundage = bcmul($this->baseAmount, $rate, Tax::SCALE);
        return $this->poundage;
    }

    protected function setBaseAmount($amount)
    {
        $this->baseAmount = $amount;
    }
}
