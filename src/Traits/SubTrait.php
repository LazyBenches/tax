<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2019/6/27
 * Time: 21:08
 */

namespace LazyBench\Tax\Traits;

trait SubTrait
{
    /**
     * Author:LazyBench
     * @param $amount
     * @param int $scale
     * @return string
     * 向上保留
     */
    public function ceil($amount, $scale = 2): string
    {
        $array = explode('.', $amount);
        $left = $array[1] ?? 0;
        $newAmount = $array[0].'.'.($left > 0 ? 1 : 0);
        $base = 10 ** $scale;
        return bcdiv(ceil(bcmul($newAmount, $base, 1)), $base, $scale);
    }

    /**
     * Author:LazyBench
     * @param $amount
     * @param int $scale
     * @return string
     * 向下保留
     */
    public function floor($amount, $scale = 2)
    {
        $base = 10 ** $scale;
        return bcdiv(floor(bcmul($amount, $base, 1)), $base, $scale);
    }
}
