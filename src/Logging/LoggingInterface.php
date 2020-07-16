<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/7/16
 * Time: 11:34
 */

namespace LazyBench\Tax\Logging;

interface LoggingInterface
{
    public function handle(array $handle);
}