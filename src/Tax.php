<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/26
 * Time: 12:46
 */

namespace LazyBench\Tax;

use LazyBench\Tax\Constant\Tax as TaxConst;
use LazyBench\Tax\Calculate\Company;
use LazyBench\Tax\Calculate\Person;
use LazyBench\Tax\Calculate\PersonIncomeAbove;
use LazyBench\Tax\Calculate\PersonIncomeBelow;
use LazyBench\Tax\Calculate\Poundage;
use LazyBench\Tax\Interfaces\UserMonth;
use LazyBench\Tax\Log\CompanyLog;
use LazyBench\Tax\Log\PersonLog;
use LazyBench\Tax\Log\PoundageLog;
use LazyBench\Tax\Traits\Calculate;

class Tax
{
    use Calculate;
    protected $config;
    /**
     * Author:LazyBench
     *
     * @var UserMonth
     */
    protected $statistics;

    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
        isset($this->config['person']['statistics']) && $this->statistics = new $this->config['person']['statistics'];
        isset($this->config['person']['monthModel']) && $this->statistics->setModel($this->config['person']['monthModel']);
        isset($this->config['person']['rateModel']) && $this->statistics->setRateModel($this->config['person']['rateModel']);
        return $this;
    }

    /**
     * Author:LazyBench
     *
     * @return UserMonth
     */
    public function getStatistics(): UserMonth
    {
        return $this->statistics;
    }

    /**
     * Author:LazyBench
     *
     * @param $personWages
     * @param $card
     * @param $month
     * @return PersonLog
     */
    public function getPersonData($personWages, $card = '', $month = ''): PersonLog
    {
        if ($card && $month) {
            $data = $this->statistics->getStaticMonth($personWages, $card, $month);
        }
        $log = new PersonLog();
        $log->idCard = $card ?? '';
        $log->month = $month ?? '';
        $log->monthTotal = $data['thisMonth'] ?? null;
        $log->isAdd = $data['isAdd'] ?? 0;
        $personWagesAlready = $data['thisMonth']->tax_wages ?? 0;
        $log->personWages = bcadd($personWages, $personWagesAlready, TaxConst::SCALE);
        $log->taxBaseYearLastMonthTotal = $data['taxBaseYearLastMonthTotal'] ?? 0;
        $log->personTaxLastTotal = $data['personTaxLastTotal'] ?? 0;
        $person = new Person($log);
        $person->handle();
        return $log;
    }

    /**
     * Author:LazyBench
     *
     * @param $personWages
     * @return CompanyLog
     */
    public function getCompanyData($personWages): CompanyLog
    {
        $log = new CompanyLog();
        $log->personWages = $personWages;
        $company = new Company($log);
        $company->handle();
        return $log;
    }

    /**
     * Author:LazyBench
     *
     * @param $poundageBase
     * @param $rate
     * @return PoundageLog
     */
    public function getPoundageData($poundageBase, $rate): PoundageLog
    {
        $log = new PoundageLog();
        $log->poundageBase = $poundageBase;
        $log->rate = $rate;
        $poundage = new Poundage($log);
        $poundage->handle();
        return $log;
    }

    /**
     * Author:LazyBench
     *
     * @param $income
     * @param $card
     * @param $month
     * @param int $isAdd
     * @return PersonLog
     */
    public function getPersonIncomeData($income, $card, $month, $isAdd = 0): PersonLog
    {
        $log = new PersonLog();
        $log->personIncomeLeft = $income;
        $log->isAdd = $isAdd;
        $log->idCard = $card;
        $log->month = $month;
        $personIncome = new PersonIncomeBelow($log);
        $log = $personIncome->handle();
        return $log;
    }

    /**
     * Author:LazyBench
     *
     * @param $max
     * @param $income
     * @param $card
     * @param $month
     * @return PersonLog
     */
    public function getPersonIncomeAboveMatchData($max, $income, $card, $month): PersonLog
    {
        $log = new PersonLog();
        $log->personIncomeLeft = $income;
        $log->isAdd = 1;
        $log->idCard = $card;
        $log->month = $month;
        $above = new PersonIncomeAbove($log);
        $log = $above->handle($max);
        return $log;
    }


    /**
     * Author:LazyBench
     *
     * @param $income
     * @param $card
     * @param $month
     * @param $rate
     * @return array
     */
    public function getPersonIncomeBelowData($income, $card, $month, $rate): array
    {
        $log = $this->getPersonIncomeData($income, $card, $month, 0);
        $companyLog = $this->getCompanyData($log->personWages);
        $poundageLog = $this->getPoundageData($companyLog->poundageBase, $rate);
        return [
            'person' => [
                'log' => $log->toArray(),
            ],
            'company' => [
                'log' => $companyLog->toArray(),
            ],
            'poundage' => [
                'log' => $poundageLog->toArray(),
            ],
        ];
    }

    /**
     * Author:LazyBench
     *
     * @param $max
     * @param $income
     * @param $card
     * @param $month
     * @param $rate
     * @return array
     */
    public function getPersonIncomeAboveData($max, $income, $card, $month, $rate): array
    {
        $log = $this->getPersonIncomeAboveMatchData($max, $income, $card, $month);
        $log->personWagesLeft = $this->ceil($log->personWagesLeft);
        $companyLog = $this->getCompanyData($log->personWagesLeft);
        $poundageLog = $this->getPoundageData($companyLog->poundageBase, $rate);
        $total = bcadd($poundageLog->poundage, $companyLog->personWages, TaxConst::SCALE);
        $total = bcadd($total, $companyLog->taxAmount, TaxConst::SCALE);
        $order = [
            'total' => '',
            'companyExpenditure' => '',
            'personalWages' => $log->personWagesLeft,
            'personalTaxBasis' => '',
            'personalIncome' => '',
            'taxAmount' => '',
            'personalTaxAmount' => '',
            'personalTaxAmountShould' => '',
            'personalAddedValueTax' => '',
            'addedValueTaxReduction' => '',
            'personalAdditionalTax' => '',
            'additionalTaxReduction' => '',
            'personalTax' => '',
            'poundage' => '',
            'bankPoundage' => '',
        ];
        $this->calculateFormat($order, $log, $companyLog, $poundageLog);
        return [
            'order' => $order,
            'person' => [
                'log' => $log->toArray(),
            ],
            'company' => [
                'log' => $companyLog->toArray(),
                'total' => $total,
            ],
            'poundage' => [
                'log' => $poundageLog->toArray(),
            ],
        ];
    }

    /***
     * Author:LazyBench
     *
     * @param $personWages
     * @param $card
     * @param $month
     * @param $rate
     * @return array
     */
    public function getCompanyTotal($personWages, $card, $month, $rate): array
    {
        $log = $this->getPersonData($personWages, $card, $month);
        $companyLog = $this->getCompanyData($log->personWagesLeft);
        $poundageLog = $this->getPoundageData($companyLog->poundageBase, $rate);
        $total = bcadd($poundageLog->poundage, $companyLog->personWages, TaxConst::SCALE);
        $total = bcadd($total, $companyLog->taxAmount, TaxConst::SCALE);
        return [
            'person' => [
                'log' => $log->toArray(),
            ],
            'company' => [
                'log' => $companyLog->toArray(),
                'total' => $total,
            ],
            'poundage' => [
                'log' => $poundageLog->toArray(),
            ],
        ];
    }

    /**
     * Author:LazyBench
     * 获取的是月税
     * @param $personWages
     * @param $rate
     * @param $idCard
     * @param $month
     * @param \Closure $closure
     * @return mixed
     */
    public function calculate($personWages, $rate, $idCard, $month, \Closure $closure = null)
    {
        $personLog = $this->getPersonData($personWages, $idCard, $month);
        $companyLog = $this->getCompanyData($personWages);
        $poundageLog = $this->getPoundageData($companyLog->poundageBase, $rate);
        if ($closure) {
            return $closure($personLog, $companyLog, $poundageLog);
        }
        return true;
    }


    /**
     * Author:LazyBench
     * 算价存储
     * @param array $order
     * @param PersonLog $personLog
     * @param CompanyLog $companyLog
     * @param PoundageLog $poundageLog
     * @return array
     */
    public function calculateFormat(array $order, PersonLog $personLog, CompanyLog $companyLog, PoundageLog $poundageLog): array
    {
        $order['taxAmount'] = $this->ceil($companyLog->taxAmount);//企业综合税赋
        $order['companyExpenditure'] = $this->ceil(bcadd($companyLog->personWages, $companyLog->taxAmount, TaxConst::SCALE));//企业实际支出

        $order['personalTax'] = $this->ceil($personLog->personTaxLeft);
        $order['personalAddedValueTax'] = $this->ceil($personLog->addTaxLeft);
        $order['personalAdditionalTax'] = $this->ceil($personLog->addTaxExtLeft);
        $order['personalIncome'] = $this->floor($personLog->personIncomeLeft);
        $order['personalTaxBasis'] = $this->ceil($this->getTaxBasis($order['personalWages']));
        $order['personalTaxAmount'] = $this->ceil($personLog->personTaxAmountLeft);
        $personTaxAdd = bcadd($order['personalAddedValueTax'], $order['personalAdditionalTax'], TaxConst::SCALE);
        $personTaxAmount = bcadd($order['personalTax'], $personTaxAdd, TaxConst::SCALE);
        $order['personalTaxAmount'] = $personTaxAmount;
        $personWages = bcadd($order['personalIncome'], $order['personalTaxAmount'], 2);
        $differentAmount = bcsub($order['personalWages'], $personWages, 2);
        $order['personalTaxAmount'] = bcadd($order['personalTaxAmount'], $differentAmount, 2);

        $order['personalStampTax'] = 0;
        $order['addedValueTaxReduction'] = $this->ceil($personLog->addTaxReductionLeft);
        $order['additionalTaxReduction'] = $this->ceil($personLog->addTaxExtReductionLeft);
        $order['personalTaxAmountShould'] = $this->ceil($personLog->taxAmountShould);
        $order['poundage'] = $this->ceil(bcdiv($poundageLog->poundage, 2, TaxConst::SCALE));//平台手续费
        $order['bankPoundage'] = $this->ceil(bcsub($poundageLog->poundage, $order['poundage'], TaxConst::SCALE));//银行通道费
        $order['total'] = $this->ceil(bcadd(bcadd($companyLog->personWages, $companyLog->taxAmount, TaxConst::SCALE), $poundageLog->poundage, TaxConst::SCALE));
        $poundage = bcadd($order['poundage'], $order['bankPoundage'], 2);
        $tax = bcadd($order['personalTaxAmount'], $order['taxAmount'], 2);
        $total = bcadd($poundage, $tax, 2);
        $total = bcadd($total, $order['personalIncome'], 2);
        $order['differentAmount'] = bcsub($order['total'], $total, 2);
        $order['poundage'] = bcadd($order['poundage'], $order['differentAmount'], 2);
        return $order;
    }

}
