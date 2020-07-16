<?php

namespace LazyBench\Tax\Http;

/**
 * Author:Robert
 *
 * Class Sign
 * @package Tax\Http
 */
class Sign
{
    /**
     * @var mixed|string
     */
    public $systemId;


    /**
     * @var mixed|string
     */
    public $rsaPubPath;

    /**
     * Sign constructor.
     * @param array $options
     * @throws \Exception
     */
    public function __construct(array $options = [])
    {
        if (isset($options['systemId'])) {
            $this->systemId = $options['systemId'];
        }
        if (isset($options['rsaPubPath'])) {
            $this->rsaPubPath = $options['rsaPubPath'];
        }
        if (!$this->systemId || !$this->rsaPubPath) {
            throw new \Exception('rsaPubPath或systemId签名参数配置错误');
        }
    }

    /**
     * Author:Robert
     *
     * @return resource
     * @throws \Exception
     */
    protected function getPublicKey()
    {
        if (!file_exists($this->rsaPubPath)) {
            throw new \Exception('公钥丢失,请检查rsaPubPath路径下公钥');
        }
        return openssl_pkey_get_public(file_get_contents($this->rsaPubPath));
    }

    /**
     * Author:Robert
     *
     * @param $cryptData
     * @return string
     */
    protected function encodeCryptData($cryptData): string
    {
        return bin2hex($cryptData);
    }

    /**
     * Author:Robert
     *
     * @return string
     * @throws \Exception
     */
    public function encrypt(): string
    {
        $data = sprintf("time:%s@system_id:%s", date('Y-m-d H:i:s'), $this->systemId);
        if (!openssl_public_encrypt($data, $cryptData, $this->getPublicKey(), OPENSSL_PKCS1_PADDING)) {
            throw new \Exception('签名失败,请检查rsaPubPath路径下公钥');
        }
        return $this->encodeCryptData($cryptData);
    }
}
