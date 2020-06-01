<?php

namespace Application\Core\Components\Tax\Calculate;

use Application\Core\Components\Constants\Tax;
use Application\Core\Components\Tax\Log\PoundageLog;
use Application\Core\Components\Tax\Traits\CalculateTrait;

/**
 * Author:LazyBench
 * Date:2019/1/9
 */
class Poundage
{
    use CalculateTrait;
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
    protected function getPoundage($rate)
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
