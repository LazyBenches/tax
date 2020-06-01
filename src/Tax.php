<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/26
 * Time: 12:46
 */

namespace Application\Core\Components\Logic;

use Application\Core\Components\Constants\Calculate;
use Application\Core\Components\Constants\OrderCode;
use Application\Core\Components\Constants\Tax;
use Application\Core\Components\Tax\Calculate\Company;
use Application\Core\Components\Tax\Calculate\Person;
use Application\Core\Components\Tax\Calculate\PersonIncomeAbove;
use Application\Core\Components\Tax\Calculate\PersonIncomeBelow;
use Application\Core\Components\Tax\Calculate\Poundage;
use Application\Core\Components\Tax\Log\CompanyLog;
use Application\Core\Components\Tax\Log\PersonLog;
use Application\Core\Components\Tax\Log\PoundageLog;
use Application\Core\Components\Tax\Traits\CalculateTrait;
use Application\Core\Components\Traits\LogicTrait;
use Application\Core\Components\Traits\StaticTrait;
use Phalcon\Di;

class TaxCalculateLogic extends Logic
{
    const scale = 8;
    use LogicTrait, StaticTrait, CalculateTrait;

    /**
     * Author:LazyBench
     *
     * @param $personWages
     * @param $card
     * @param $month
     * @return \Application\Core\Components\Tax\Log\PersonLog
     */
    protected function getPersonData($personWages, $card = '', $month = '')
    {
        if ($card && $month) {
            $data = UserStaticMonthLogic::getStaticMonth($personWages, $card, $month);
        }
        $log = new PersonLog();
        $log->idCard = $card ?? '';
        $log->month = $month ?? '';
        $log->monthTotal = $data['thisMonth'] ?? null;
        $log->isAdd = $data['isAdd'] ?? 0;
        $personWagesAlready = $data['thisMonth']->tax_wages ?? 0;
        $log->personWages = bcadd($personWages, $personWagesAlready, Tax::SCALE);
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
    protected function getCompanyData($personWages)
    {
        $log = new CompanyLog();
        $log->personWages = $personWages;
        $company = new Company($log);
        $company->handle();
        return $log;
    }

    protected function getPoundageData($poundageBase, $rate)
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
    protected function getPersonIncomeData($income, $card, $month, $isAdd = 0)
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

    protected function getPersonIncomeAboveMatchData($max, $income, $card, $month)
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
    protected function getPersonIncomeBelowData($income, $card, $month, $rate)
    {
        $log = $this->getPersonIncomeData($income, $card, $month, 0);
        $companyLog = TaxCalculateLogic::getCompanyData($log->personWages);
        $poundageLog = TaxCalculateLogic::getPoundageData($companyLog->poundageBase, $rate);
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
    protected function getPersonIncomeAboveData($max, $income, $card, $month, $rate)
    {
        $log = $this->getPersonIncomeAboveMatchData($max, $income, $card, $month);
        $log->personWagesLeft = $this->ceil($log->personWagesLeft);
        $companyLog = TaxCalculateLogic::getCompanyData($log->personWagesLeft);
        $poundageLog = TaxCalculateLogic::getPoundageData($companyLog->poundageBase, $rate);
        $total = bcadd($poundageLog->poundage, $companyLog->personWages, Tax::SCALE);
        $total = bcadd($total, $companyLog->taxAmount, Tax::SCALE);
        $order = new \Order();
        $order->personal_wages = $log->personWagesLeft;
        CalculateLogic::calculateFormat($order, $log, $companyLog, $poundageLog);
        return [
            'order' => $order->toArray([
                "total",
                "company_expenditure",
                "personal_wages",
                "personal_tax_basis",
                "personal_income",
                "tax_amount",
                "personal_tax_amount",
                "personal_tax_amount_should",
                "personal_added_value_tax",
                "added_value_tax_reduction",
                "personal_additional_tax",
                "additional_tax_reduction",
                "personal_tax",
                "poundage",
                "bank_poundage",
            ]),
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
    protected function getCompanyTotal($personWages, $card, $month, $rate)
    {
        $log = $this->getPersonData($personWages, $card, $month);
        $companyLog = TaxCalculateLogic::getCompanyData($log->personWagesLeft);
        $poundageLog = TaxCalculateLogic::getPoundageData($companyLog->poundageBase, $rate);
        $total = bcadd($poundageLog->poundage, $companyLog->personWages, Tax::SCALE);
        $total = bcadd($total, $companyLog->taxAmount, Tax::SCALE);
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
     * @param \Closure $closure
     * @return mixed
     */
    protected function calculate($personWages, $rate, $idCard, \Closure $closure = null)
    {
        $month = date('Ym');
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
     *
     * @param \Order $order
     * @param null $nextDay
     * @return bool
     * @throws \Exception
     */
    protected function calculateAsync(\Order $order, $nextDay = null)
    {
        $return = $order->transaction(function (\Order $order) {
            $id = $order->id;
            $order = \Order::findFirst([
                'conditions' => 'id=:id:',
                'bind' => [
                    'id' => $id,
                ],
                'for_update' => true,
            ]);
            if ($order->status != OrderCode::PENDING_STATUS) {
                return false;
            }
            $order->status = OrderCode::CALCULATING_STATUS;
            if (!$order->update()) {
                return false;
            }
            return true;
        });
        if (!$return) {
            return false;
        }
        $nextDay = $nextDay ?: strtotime('+1day');
        $paymentProducer = Di::getDefault()->get('paymentProducer');
        return $paymentProducer->handle([
            'id' => $order->id,
            'releaseEnd' => $nextDay,
        ], 2, 'CalculateOrder');
    }
}
