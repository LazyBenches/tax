<?php

namespace LazyBench\Tax\Log;

/**
 * Author:LazyBench
 * Date:2019/1/11
 */
abstract class Log
{

    /**
     * Author:LazyBench
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
