<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/26
 * Time: 15:27
 */

namespace Application\Core\Components\Tax\Log;

class CompanyLog extends Log
{
    /**
     * Author:LazyBench
     * 企业发放金额
     * @var
     */
    public $personWages;

    /**
     * Author:LazyBench
     * 增值税
     * @var
     */
    public $addTax;

    /**
     * Author:LazyBench
     * 增值附加税
     * @var
     */
    public $addTaxExt;

    /**
     * Author:LazyBench
     * 印花税
     * @var
     */
    public $stampTax;

    /**
     * Author:LazyBench
     * 综合税
     * @var
     */
    public $taxAmount;

    /**
     * Author:LazyBench
     * 企业实际支出
     * @var
     */
    public $poundageBase;
}
