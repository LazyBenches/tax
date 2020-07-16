<?php

namespace LazyBench\Tax\Requests;

/**
 * 获取Token服务
 * Author:Robert
 *
 * Class TokenRequest
 * @package Tax\Requests
 */
class TokenRequest extends BaseRequest implements RequestInterface
{

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function requireToken(): bool
    {
        return false;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'getLpxx0001';
    }
}
