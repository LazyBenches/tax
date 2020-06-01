<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/26
 * Time: 15:52
 */

namespace Application\Core\Components\Tax\Log;

class PoundageLog extends Log
{
    /**
     * Author:LazyBench
     * 企业
     * @var
     */
    public $poundageBase;

    public $rate = 0.01;

    public $poundage;
}
