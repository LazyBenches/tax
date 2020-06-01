<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/6/1
 * Time: 15:48
 */

namespace LazyBench\Tax\Interfaces;


interface UserMonth
{
    /**
     * Author:LazyBench
     * 设置统计model
     * @param $model
     * @return mixed
     */
    public function setModel($model);

    /**
     * Author:LazyBench
     * 设置费率model
     * @param $model
     * @return mixed
     */
    public function setRateModel($model);

    /**
     * Author:LazyBench
     * 当年，累计到上个月的已纳税额(应纳税额统计)
     * H（1-3月的H）*I*K-L
     * @param $idCard
     * @param $dateMonth
     * @return bool
     */
    public function getTaxAmountLast($idCard, $dateMonth);


    /**
     * Author:LazyBench
     * 当年，累计到上月的应税收入
     * @param $idCard
     * @param $dateMonth
     * @return bool
     */
    public function getTaxIncomeTotal($idCard, $dateMonth);

    /**
     * Author:LazyBench
     * 获取上月个人所得税
     * @param $idCard
     * @param $month
     * @return float|int
     */
    public function getLastPersonTaxTotal($idCard, $month);


    /**
     * Author:LazyBench
     * 获取上月税基累计
     * @param $idCard
     * @param $month
     * @return float|int
     */
    public function getTaxBaseYearTotal($idCard, $month);

    /**
     * Author:LazyBench
     *
     * @param $idCard
     * @param $dateMonth
     * @return mixed
     */
    public function getTaxBaseLastTenMonth($idCard, $dateMonth);

    /**
     * Author:LazyBench
     *
     * @param $card
     * @param $month
     * @return mixed
     */
    public function getThisMonth($card, $month);

    /**
     * Author:LazyBench
     * @param $amount
     * @return mixed
     * 获取计税依据
     */
    public function getTaxBasis($amount = 0);

    public function getStaticMonth($personWages, $card, $month);
}