<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/28
 * Time: 15:38
 */

namespace LazyBench\Tax\Statistics;

use LazyBench\Tax\Constant\Tax;

class UserMonth implements \LazyBench\Tax\Interfaces\UserMonth
{
    protected $card;
    protected $month;
    protected $personWages;
    protected $monthModel;
    protected $rateModel;
    protected $data = [
        'taxBaseYearLastMonthTotal' => 0.00,
        'personTaxLastTotal' => 0.00,
        'thisMonth' => null,
        'isAdd' => 0,
    ];

    public function setModel($model)
    {
        $this->monthModel = $model;
        return $this;
    }

    /**
     * Author:LazyBench
     * 设置费率model
     * @param $model
     * @return mixed
     */
    public function setRateModel($model)
    {
        $this->rateModel = $model;
        return $this;
    }

    /**
     * Author:LazyBench
     * @param $amount
     * @return mixed
     * 获取计税依据
     */
    public function getTaxBasis($amount = 0)
    {
        if ($amount) {
            return bcdiv($amount, 1.03, Tax::SCALE);
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
     * @return mixed
     */
    public function getThisMonth($card, $month)
    {
        return '';
    }


    public function getStaticMonth($personWages, $card, $month)
    {
        if ($this->card !== $card || $this->month !== $month || $this->personWages !== $personWages) {
            $user = new User();
            $taxBaseYearLastMonthTotal = $this->getTaxBaseYearTotal($card, $month);
            $personTaxLastTotal = $this->getLastPersonTaxTotal($card, $month);
            $thisMonth = $this->getThisMonth($card, $month);
            $personWages = bcadd($personWages, $thisMonth->taxWages, Tax::SCALE);//月累计税前
            $basis = $this->getTaxBasis($personWages);
            $isAdd = $user->isAddTax($basis, $this->getTaxBaseLastTenMonth($card, $month));
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
}
