<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/28
 * Time: 15:38
 */

namespace LazyBench\Tax\Statistics;

use LazyBench\Tax\Calculate\Person;
use LazyBench\Tax\Constant\Tax;

class UserMonth implements \LazyBench\Tax\Interfaces\UserMonth
{
    protected $card;
    protected $month;
    protected $personWages;
    protected $data = [
        'taxBaseYearLastMonthTotal' => 0.00,
        'personTaxLastTotal' => 0.00,
        'thisMonth' => null,
        'isAdd' => 0,
    ];


    /**
     * Author:LazyBench
     * @param $amount
     * @return mixed
     * 获取计税依据
     */
    public function getTaxBasis($amount = 0)
    {
        if ($amount) {
            return bcdiv($amount, Person::getBaseRate(), Tax::SCALE);
        }
        return 0;
    }

    /**
     * Author:LazyBench
     * 当年，累计到上个月的已纳税额(应纳税额统计)
     * H（1-3月的H）*I*K-L
     * @param $idCard
     * @param $dateMonth
     * @return bool
     */
    public function getTaxAmountLast($idCard, $dateMonth)
    {
        return '';
    }


    /**
     * Author:LazyBench
     * 当年，累计到上月的应税收入
     * @param $idCard
     * @param $dateMonth
     * @return bool
     */
    public function getTaxIncomeTotal($idCard, $dateMonth)
    {
        return '';
    }

    /**
     * Author:LazyBench
     * 获取上月个人所得税
     * @param $idCard
     * @param $month
     * @return float|int
     */
    public function getLastPersonTaxTotal($idCard, $month)
    {
        return 0;
    }


    /**
     * Author:LazyBench
     * 获取上月税基累计
     * @param $idCard
     * @param $month
     * @return float|int
     */
    public function getTaxBaseYearTotal($idCard, $month)
    {
        return '';
    }

    /**
     * Author:LazyBench
     * 获取前11个月 到 当前月 税基
     * @param $idCard
     * @param $dateMonth
     * @return mixed
     */
    public function getTaxBaseLastTenMonth($idCard, $dateMonth)
    {
        return '';

    }

    /**
     * Author:LazyBench
     * 获取当前月统计
     * @param $card
     * @param $month
     * @return array
     */
    public function getThisMonth($card, $month): array
    {
        return [
            'taxWages' => 0,
        ];
    }


    public function getStaticMonth($personWages, $card, $month)
    {
        if ($this->card !== $card || $this->month !== $month || $this->personWages !== $personWages) {
            $taxBaseYearLastMonthTotal = $this->getTaxBaseYearTotal($card, $month);
            $personTaxLastTotal = $this->getLastPersonTaxTotal($card, $month);
            $thisMonth = $this->getThisMonth($card, $month);
            $personWages = bcadd($personWages, $thisMonth['taxWages'], Tax::SCALE);//月累计税前
            $basis = $this->getTaxBasis($personWages);
            $isAdd = $this->isAddTax($basis, $this->getTaxBaseLastTenMonth($card, $month));
            $this->card = $card;
            $this->month = $month;
            $this->data = [
                'taxBaseYearLastMonthTotal' => $taxBaseYearLastMonthTotal,
                'personTaxLastTotal' => $personTaxLastTotal,
                'thisMonth' => $thisMonth,
                'isAdd' => $isAdd,
            ];
        }
        return $this->data;
    }

    /**
     * Author:LazyBench
     * 获取个人所得税率表
     * @return array|mixed
     */
    public function getPersonalTaxRate()
    {
        //        $getPersonalTaxRateSettings = \ServiceChargeTable::find([
        //            'conditions' => '[from]<= :income: and company_id=0',
        //            'bind' => [
        //                'income' => $total,
        //            ],
        //            'order' => 'id ASC',
        //        ])->toArray();
        return [
            0 => [
                'from' => '0',
                'to' => '30000',
                'rate' => '0.05',
            ],
            1 => [
                'from' => '30000',
                'to' => '90000',
                'rate' => '0.10',
            ],
            2 => [
                'from' => '90000',
                'to' => '300000.00',
                'rate' => '0.20',
            ],
            3 => [
                'from' => '300000.00',
                'to' => '500000.00',
                'rate' => '0.30',
            ],
            4 => [
                'from' => '500000.00',
                'to' => '99999999.00',
                'rate' => '0.35',
            ],
        ];
    }

    /**
     * Author:LazyBench
     * 是否缴纳增值附加
     * @param $basis
     * @param $taxBasis
     * @return int
     */
    public function isAddTax($basis, $taxBasis = 0): int
    {
        $isAddTax = ($basis > Person::getBasisTax()) ?: false;//是否缴纳增值附加
        if ($isAddTax) {
            return 1;
        }
        if (bcadd($taxBasis, $basis, 2) > Person::getBasisTaxYear()) {
            return 1;
        }
        return 0;
    }


    /**
     * Author:LazyBench
     * 应纳税额统计
     * @param $incomeTotal
     * @param $rate
     * @return string
     */
    public function getTaxAmountTotal($incomeTotal, $rate): string
    {
        $taxBasis = bcmul($incomeTotal, Tax::RATE_DISCOUNT, Tax::SCALE);
        return bcsub(bcmul($taxBasis, $rate / 100, Tax::SCALE), $this->rateMap[$rate], Tax::SCALE);
    }


    /**
     * Author:LazyBench
     * 税率
     * @param $amount
     * @return int|string
     */
    public function switchRate($amount)
    {
        foreach ($this->rowMap as $rate => $value) {
            if ($amount > $value[0] && $amount <= $value[1]) {
                return $rate;
            }
        }
    }
}
