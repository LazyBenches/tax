<?php

namespace LazyBench\Tax\Http;

class ResultSet
{

    /**
     * 返回的错误码
     **/
    public $code;
    /**
     * Author:Robert
     *
     * @var
     */
    public $status;

    /**
     * 返回的错误信息
     **/
    public $message = '';

    /**
     * Author:Robert
     *
     * @var
     */
    public $data;

    public const SUCCESS_STATUS = 'success';


    public const ERROR_STATUS = 'error';


    public const CLIENT_VALIDATION_ERROR_CODE = '4000';
    public const HTTP_INTERNAL_SERVER_ERROR_CODE = '5000';
    public const HTTP_NO_CONTENT_CODE = '2004';
    public const API_100_CODE = '100';
    public const API_101_CODE = '101';
    public const API_102_CODE = '102';
    public const API_103_CODE = '103';
    public const API_104_CODE = '104';
    public const API_105_CODE = '105';
    public const API_106_CODE = '106';
    public const API_107_CODE = '107';
    public const API_200_CODE = '200';
    public const API_300_CODE = '300';
    public const API_400_CODE = '400';
    public const API_401_CODE = '401';
    public const API_402_CODE = '402';
    public const API_403_CODE = '403';
    public const API_404_CODE = '404';
    public const API_405_CODE = '405';
    public const API_406_CODE = '406';
    public const API_407_CODE = '407';
    public const API_408_CODE = '408';
    public const API_409_CODE = '409';
    public const API_410_CODE = '410';
    public const API_411_CODE = '411';
    public const API_412_CODE = '412';
    public const API_500_CODE = '500';
    public const API_501_CODE = '501';
    public const API_502_CODE = '502';
    public const API_503_CODE = '503';
    public const API_504_CODE = '504';
    public const API_505_CODE = '505';
    public const API_506_CODE = '506';
    public const API_507_CODE = '507';
    public const API_508_CODE = '508';
    public const API_509_CODE = '509';
    public const API_510_CODE = '510';
    public const API_511_CODE = '511';
    public const API_512_CODE = '512';
    public const API_MINUS_1_CODE = '-1';
    public const API_16_CODE = '16';

    public const API_ERROR_CODE = [
        self::CLIENT_VALIDATION_ERROR_CODE => '验证器报错',
        self::HTTP_NO_CONTENT_CODE => '没有返回客户端错误',
        self::HTTP_INTERNAL_SERVER_ERROR_CODE => '服务端返回异常',
        self::API_100_CODE => '请求成功',
        self::API_101_CODE => '平台数据请求成功',
        self::API_102_CODE => '发布方数据请求成功',
        self::API_103_CODE => '服务方数据请求成功',
        self::API_104_CODE => '订单信息数据请求成功',
        self::API_105_CODE => '申报信息数据请求成功',
        self::API_106_CODE => '个税汇总信息数据请求成功',
        self::API_107_CODE => '个税明细信息数据请求成功',
        self::API_200_CODE => '内部系列代码',
        self::API_300_CODE => '内部系列代码',
        self::API_400_CODE => '请求错误',
        self::API_401_CODE => 'service_id为空',
        self::API_402_CODE => 'system_id为空',
        self::API_403_CODE => 'sign为空',
        self::API_404_CODE => 'sign验签失败',
        self::API_405_CODE => 'token为空',
        self::API_406_CODE => 'token获取失败',
        self::API_407_CODE => 'token验证失败',
        self::API_408_CODE => 'token失效',
        self::API_409_CODE => 'seq为空',
        self::API_410_CODE => 'timestamp为空',
        self::API_411_CODE => 'version为空',
        self::API_412_CODE => 'charset为空',
        self::API_500_CODE => '请求业务数据存在错误',
        self::API_501_CODE => '请求业务数据列表为空',
        self::API_502_CODE => '平台数据请求错误',
        self::API_503_CODE => '发布方数据请求错误',
        self::API_504_CODE => '服务方数据请求错误',
        self::API_505_CODE => '订单信息数据请求错误',
        self::API_506_CODE => '申报信息数据请求请求错误',
        self::API_507_CODE => '个税汇总信息数据请求错误',
        self::API_508_CODE => '个税明细信息数据请求错误',
        self::API_509_CODE => '数据元素提取错误',
        self::API_510_CODE => '数据元素清洗错误',
        self::API_511_CODE => '数据审核错误',
        self::API_512_CODE => '数据开票错误',
        self::API_MINUS_1_CODE => '其它业务异常',
        self::API_16_CODE => '查询异常',
    ];

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCodeMsg(): string
    {
        return self::API_ERROR_CODE[$this->code] ?? '无';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS_STATUS;
    }

    /**
     * Author:Robert
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
