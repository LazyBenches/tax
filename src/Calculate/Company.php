<?php

namespace LazyBench\Tax\Calculate;

use LazyBench\Tax\Constant\Tax;
use LazyBench\Tax\Log\CompanyLog;
use LazyBench\Tax\Traits\Calculate;

/**
 * Author:LazyBench
 * Date:2019/1/8
 * 公司
 */
class Company
{
    use Calculate;
    const RATES = [
        0.07,
        0.03,
        0.02,
    ];


    protected $log;
    /**
     * Author:LazyBench
     * Date:2019/1/8
     * @var
     * 企业发放金额
     */
    protected $companyPayment;
    /**
     * Author:LazyBench
     * Date:2019/1/8
     * @var
     * 企业应缴增值税
     */
    protected $companyValueAddedTax;

    /**
     * 企业应缴附加税
     * Author:Robert
     *
     * @var
     */
    protected $companyAdditionalTax;

    /**
     * 企业应缴印花税
     * Author:Robert
     *
     * @var
     */
    protected $companyStampTax;

    /**
     * 企业印花税税率
     */
    const STAMP_RATE = 0.0003;

    /**
     * 企业综合税费
     * Author:Robert
     *
     * @var
     */
    protected $companyTaxAmount;

    /**
     * 企业实际支出
     * Author:Robert
     *
     * @var
     */
    protected $companyExpenditure;

    /**
     * Company constructor.
     * @param CompanyLog $log
     */
    public function __construct(CompanyLog $log)
    {
        $this->log = $log;
    }

    public function handle()
    {
        $this->setPaymentAmount($this->log->personWages);
        $this->log->addTax = $this->getCompanyValueAddedTax();//企业应缴纳增值税
        $this->log->addTaxExt = $this->getCompanyAdditionalTax();//企业应缴附加税
        $this->log->stampTax = $this->getCompanyStampTax();//企业应缴印花税
        $this->log->taxAmount = $this->getCompanyTaxAmount();//企业综合税赋
        $this->log->poundageBase = $this->getCompanyExpenditure();//企业
        return $this->log;
    }


    /**
     * Author:LazyBench
     * @param $amount
     */
    protected function setPaymentAmount($amount)
    {
        $this->companyPayment = $amount;
    }

    /**
     * Author:LazyBench
     * @return string
     * 企业应缴纳增值税
     */
    protected function getCompanyValueAddedTax()
    {
        if (!$this->companyValueAddedTax) {
            $valueAddedTaxRate = 0.06; //企业增值税税率
            $this->companyValueAddedTax = bcmul($this->companyPayment, $valueAddedTaxRate, Tax::SCALE);
        }
        return $this->companyValueAddedTax;
    }

    /**
     * 企业应缴附加税
     * Author:Robert
     * 向上收
     * @return mixed
     */
    protected function getCompanyAdditionalTax()
    {
        if ($this->companyAdditionalTax) {
            return $this->companyAdditionalTax;
        }
        $total = $this->getCompanyValueAddedTax();
        return $this->getBasisRateTax($total);
    }


    /**
     * 企业应缴印花税
     * Author:Robert
     * 向上收
     * @return mixed
     */
    protected function getCompanyStampTax()
    {
        if ($this->companyStampTax) {
            return $this->companyStampTax;
        }
        $total = bcadd($this->getCompanyValueAddedTax(), $this->companyPayment, Tax::SCALE);
        $this->companyStampTax = bcmul($total, self::STAMP_RATE, Tax::SCALE);
        return $this->companyStampTax;
    }

    /**
     * 企业综合税费
     * Author:Robert
     * (企业应缴增值税+企业应缴附加税+企业应缴印花税) 向上收
     * @return string
     */
    protected function getCompanyTaxAmount()
    {
        if ($this->companyTaxAmount) {
            return $this->companyTaxAmount;
        }
        $total = $this->getCompanyValueAddedTax();
        $total = bcadd($total, $this->getCompanyAdditionalTax(), Tax::SCALE);
        $this->companyTaxAmount = bcadd($total, $this->getCompanyStampTax(), Tax::SCALE);
        return $this->companyTaxAmount;
    }

    /**
     * 企业实际支出
     * Author:Robert
     * (企业综合税费+企业发放金额)
     * @return string
     */
    protected function getCompanyExpenditure()
    {
        if ($this->companyExpenditure) {
            return $this->companyExpenditure;
        }
        $this->companyExpenditure = bcadd($this->companyPayment, $this->getCompanyTaxAmount(), Tax::SCALE);
        return $this->companyExpenditure;
    }
}
