<?php

namespace Application\Core\Components\Tax\Log;

/**
 * Author:LazyBench
 * Date:2019/1/11
 */
class Log
{


    public function toArray()
    {
        return get_object_vars($this);
    }
}
