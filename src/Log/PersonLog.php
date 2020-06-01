<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/26
 * Time: 10:21
 * 个人所得税，增值附加都是按月收取
 * 计税依据是按年累计
 */

namespace Application\Core\Components\Tax\Log;

class PersonLog extends Log
{
    public $idCard;
    /**
     * Author:LazyBench
     * 月份
     * @var
     */
    public $month;

    /**
     * Author:LazyBench
     * 月累计详情
     * @var
     */
    public $monthTotal;
    /**
     * Author:LazyBench
     * 是否缴纳增值附加
     * @var bool
     */
    public $isAdd = false;
    /**
     * Author:LazyBench
     * 月税前
     * @var
     */
    public $personWages;

    /**
     * Author:LazyBench
     * 当月剩余应发放金额
     * @var
     */
    public $personWagesLeft;
    /**
     * Author:LazyBench
     * 累计1到上个月税基
     * @var
     */
    public $taxBaseYearLastMonthTotal;

    /**
     * Author:LazyBench
     * 累计1到当前月个税
     * @var
     */
    public $personTaxTotal;
    /**
     * Author:LazyBench
     * 累计1到上个月个税
     * @var
     */
    public $personTaxLastTotal;
    /**
     * Author:LazyBench
     * 当月总增值税
     * @var
     */
    public $addTax;


    /**
     * Author:LazyBench
     * 当月总增值税剩余须缴纳
     * @var
     */
    public $addTaxLeft;

    /**
     * Author:LazyBench
     * 当月总增值附加税
     * @var
     */
    public $addTaxExt;


    /**
     * Author:LazyBench
     * 当月总增值附加税剩余须缴纳
     * @var
     */
    public $addTaxExtLeft;

    /**
     * Author:LazyBench
     * 个人所得税
     * @var
     */
    public $personTax;


    /**
     * Author:LazyBench
     * 本月剩余须缴纳个人所得税
     * @var
     */

    public $personTaxLeft;

    /**
     * Author:LazyBench
     * 本月税基
     * @var
     */
    public $taxBase;

    /**
     * Author:LazyBench
     * 个人税后所得
     * @var
     */
    public $personIncome;


    /**
     * Author:LazyBench
     * 个人税后所得剩余须缴纳
     * @var
     */
    public $personIncomeLeft;


    /**
     * Author:LazyBench
     * 人综合赋税
     * @var
     */
    public $personTaxAmount;


    /**
     * Author:LazyBench
     * 人综合赋税剩余须缴纳
     * @var
     */
    public $personTaxAmountLeft;

    /**
     * Author:LazyBench
     * 本月减免增值税
     * @var
     */
    public $addTaxReduction;

    /**
     * Author:LazyBench
     * 本月减免增值附加
     * @var
     */
    public $addTaxExtReduction;

    /**
     * Author:LazyBench
     * 本月减免增值附加 细节
     * @var
     */
    public $addTaxExtReduceDetail;

    /**
     * Author:LazyBench
     *
     * @var
     */
    public $taxAmountShould;

    /**
     * Author:LazyBench
     * 本次减免增值税
     * @var
     */
    public $addTaxReductionLeft;

    /**
     * Author:LazyBench
     * 本次减免增值附加
     * @var
     */
    public $addTaxExtReductionLeft;
}
