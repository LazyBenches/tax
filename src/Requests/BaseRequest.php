<?php

namespace LazyBench\Tax\Requests;

/**
 * Author:Robert
 *
 * Class BaseRequest
 * @package Tax\Requests
 */
abstract class BaseRequest
{

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var
     */
    protected $token;

    /**
     * @var
     */
    private $_message = '';


    /**
     * Author:Robert
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * Author:Robert
     *
     * @param string $msg
     * @return string
     */
    public function setMessage(string $msg)
    {
        return $this->_message = $msg;
    }

    /**'
     * Author:Robert
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return !!$this->_message;
    }

    /**
     * Author:Robert
     *
     * @return array
     */
    public function getBody(): array
    {
        return [$this->params];
    }


    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return '';
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function requireToken(): bool
    {
        return true;
    }
}
