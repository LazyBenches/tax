<?php

namespace LazyBench\Tax\Http;

use LazyBench\Tax\Logging\LoggingInterface;
use LazyBench\Tax\Requests\RequestInterface;
use LazyBench\Tax\Requests\TokenRequest;

class Client
{
    /**
     * Author:LazyBench
     *
     * @var $loggingDriver LoggingInterface
     */
    protected $loggingDriver;
    /**
     * Author:Robert
     *
     * @var int
     */
    protected $httpTimeout = 2;

    /**
     * Author:Robert
     *
     * @var int
     */
    protected $connectTimeout = 2;

    /**
     * Author:Robert
     *
     * @var string
     */
    protected $version = '1.0';

    /**
     * Author:Robert
     *
     * @var string
     */
    protected $gatewayUrl = 'http://47.105.216.166:8083';

    /**
     * Author:Robert
     *
     * @var string
     */
    protected $systemId = '';

    /**
     * Author:Robert
     *
     * @var
     */
    protected $logFile;


    /**
     * Author:Robert
     *
     * @var
     */
    private $requestInfo;

    /**
     * Author:Robert
     *
     * @var
     */
    private $response;


    /**
     * Author:Robert
     *
     * @var
     */
    private $sign;

    /**
     * @var
     */
    private $cacheFile;

    /**
     *
     */
    public const HTTP_USER_AGENT = 'H.Y.D Bot V0.01';

    /**
     * Client constructor.
     * @param $options
     * @throws \Exception
     */
    public function __construct($options)
    {
        if (isset($options['systemId'])) {
            $this->systemId = $options['systemId'];
        }
        if (isset($options['version'])) {
            $this->version = $options['version'];
        }
        if (isset($options['gatewayUrl'])) {
            $this->gatewayUrl = $options['gatewayUrl'];
        }
        if (isset($options['httpTimeout'])) {
            $this->httpTimeout = $options['httpTimeout'];
        }
        if (isset($options['connectTimeout'])) {
            $this->connectTimeout = $options['connectTimeout'];
        }
        if (isset($options['logDriver']['class']) && class_exists($options['logDriver']['class'])) {
            $driver = $options['logDriver']['class'];
            $config = $options['logDriver']['config'] ?? [];
            $this->loggingDriver = new $driver($config);
        }
        if (isset($options['cacheFile'])) {
            $this->cacheFile = $options['cacheFile'];
        }
        $this->sign = new Sign($options);
    }

    /**
     * Author:Robert
     *
     * @param $url
     */
    public function setGatewayUrl($url)
    {
        $this->gatewayUrl = $url;
    }

    /**
     * Author:Robert
     *
     * @return mixed
     */
    public function debug()
    {
        return [
            'request' => $this->requestInfo,
            'response' => $this->response,
        ];
    }

    /**
     * Author:Robert
     *
     * @param string $file
     * @return string
     */
    public static function imageBase64Encode(string $file): string
    {
        $type = getimagesize($file);
        $content = file_get_contents($file);
        $binary = chunk_split(base64_encode($content));
        $imgTypeMap = [
            '1' => 'gif',
            '2' => 'jpg',
            '3' => 'png',
        ];
        $type = $imgTypeMap[$type[2]] ?? 'jpg';
        return 'data:image/'.$type.';base64,'.$binary;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    private function makeSeq(): string
    {
        return md5(uniqid('', true));
    }

    /**
     * Author:Robert
     *
     * @return bool|mixed
     */
    protected function getTokenFromCache()
    {
        if (!$this->cacheFile || !file_exists($this->cacheFile)) {
            return false;
        }
        $cache = @file_get_contents($this->cacheFile);
        if (!$cache) {
            return false;
        }
        $data = unserialize($cache);
        $token = $data->token ?? '';
        $expiresAt = $data->expires_at ?? '';
        if (!$token || !$expiresAt || time() > strtotime($data->expires_at) - 3600) {
            return false;
        }
        return $data;
    }

    /**
     * Author:Robert
     *
     * @param $data
     * @return bool
     * @throws \Exception
     */
    protected function setTokenToCache($data)
    {
        if ($this->cacheFile && !@file_put_contents($this->cacheFile, serialize($data))) {
            throw new \Exception('无法写入token cache，请检查'.$this->cacheFile.'可写入');
        }
        return true;
    }

    protected function clearTokenCache()
    {
        file_exists($this->cacheFile) && unlink($this->cacheFile);
    }

    /**
     * Author:Robert
     *
     * @return string
     * @throws \Exception
     */
    protected function getToken(): string
    {
        $data = $this->getTokenFromCache();
        if (!$data) {
            $tokenRequest = new TokenRequest();
            $response = $this->execute($tokenRequest);
            if (!$response->isSuccess()) {
                throw new \Exception('['.$response->getCode().']'.$response->getMessage(), $response->getCode());
            }
            $data = $response->getData();
            $this->setTokenToCache($data);
        }
        return $data->token;
    }

    /**
     * Author:Robert
     *
     * @param RequestInterface $request
     * @return ResultSet
     * @throws \Exception
     */
    public function execute(RequestInterface $request): ResultSet
    {
        $result = new ResultSet();
        if ($request->validate() === false) {
            $result->code = ResultSet::CLIENT_VALIDATION_ERROR_CODE;
            $result->message = $request->getMessage();
            $result->status = ResultSet::ERROR_STATUS;
            return $result;
        }
        $nodeName = $request->getNodeName();
        $postFields = $nodeName ? [$nodeName => $request->getBody()] : $request->getBody();
        $postFields = array_merge($postFields, [
            'service_id' => $request->getServiceId(),
            'system_id' => $this->systemId,
            'sign' => $this->sign->encrypt(),
            'seq' => $this->makeSeq(),
            'charset' => 'UTF-8',
            'timestamp' => (string)date('Y-m-d H:i:s'),
            'version' => $this->version,
        ]);
        if ($request->requireToken()) {
            $postFields['token'] = $this->getToken();
        }
        try {
            $resp = $this->httpPost($this->gatewayUrl.$request->getServiceId(), $postFields ? json_encode($postFields) : '');
        } catch (\Exception $e) {
            $result->code = ResultSet::HTTP_INTERNAL_SERVER_ERROR_CODE;
            $result->message = ResultSet::API_ERROR_CODE[ResultSet::HTTP_INTERNAL_SERVER_ERROR_CODE];
            $result->status = ResultSet::ERROR_STATUS;
            $this->clearTokenCache();
            return $result;
        }
        if (!$resp) {
            $this->clearTokenCache();
            $result->code = ResultSet::HTTP_NO_CONTENT_CODE;
            $result->status = ResultSet::ERROR_STATUS;
            $result->message = ResultSet::API_ERROR_CODE[ResultSet::HTTP_NO_CONTENT_CODE];
            return $result;
        }
        $respObject = json_decode($resp);
        if (!$respObject || !isset($respObject->code) || !isset($respObject->desc)) {
            $result->code = ResultSet::HTTP_NO_CONTENT_CODE;
            $result->status = ResultSet::ERROR_STATUS;
            $result->message = ResultSet::API_ERROR_CODE[ResultSet::HTTP_NO_CONTENT_CODE];
            $this->clearTokenCache();
            return $result;
        }
        $result->code = $respObject->code;
        $result->message = $respObject->desc;
        if ($respObject->code != ResultSet::API_100_CODE) {
            $result->status = ResultSet::ERROR_STATUS;
            $this->clearTokenCache();
            return $result;
        }
        $result->status = ResultSet::SUCCESS_STATUS;
        $result->data = $respObject;
        return $result;
    }

    /**
     * Author:Robert
     *
     * @param $msg
     */
    public function writeLog(string $msg)
    {
        $this->loggingDriver->handle($msg);
    }

    /**
     * Author:Robert
     *
     * @param $url
     * @return bool
     */
    private function isSSL($url): bool
    {
        return 0 === strpos($url, 'https');
    }

    /**
     * Author:Robert
     *
     * @param $url
     * @param string $postBodyString
     * @return mixed
     * @throws \Exception
     */
    private function httpPost($url, string $postBodyString)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->isSSL($url)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->httpTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($ch, CURLOPT_USERAGENT, self::HTTP_USER_AGENT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Except',
            //            'Content-Length: '.strlen($postBodyString),
        ]);
        $this->response = curl_exec($ch);
        $this->requestInfo = curl_getinfo($ch);
        if ($this->requestInfo) {
            $this->requestInfo['body'] = $postBodyString;
        }
        if ($this->loggingDriver) {
            $date = date('Y-m-d H:i:s');
            $this->writeLog("[{$date}][request]->".json_encode($this->requestInfo));
            $this->writeLog("[{$date}][response]->{$this->response}");
        }
        $curlErrorCode = curl_errno($ch);
        if ($curlErrorCode) {
            throw new \Exception(curl_error($ch), $curlErrorCode);
        }
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 !== $httpStatusCode) {
            throw new \Exception($this->response, $httpStatusCode);
        }
        curl_close($ch);
        return $this->response;
    }
}
