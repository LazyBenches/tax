<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/27
 * Time: 21:08
 */

namespace Application\Core\Components\Tax\Traits;

trait SubTrait
{
    /**
     * Author:LazyBench
     * @param $amount
     * @param int $scale
     * @return string
     * 向上保留
     */
    protected function ceil($amount, $scale = 2)
    {
        $base = pow(10, $scale);
        return bcdiv(ceil(bcmul($amount, $base, 1)), $base, $scale);
    }

    /**
     * Author:LazyBench
     * @param $amount
     * @param int $scale
     * @return string
     * 向下保留
     */
    protected function floor($amount, $scale = 2)
    {
        $base = pow(10, $scale);
        return bcdiv(floor(bcmul($amount, $base, 1)), $base, $scale);
    }
}
