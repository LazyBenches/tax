<?php

namespace LazyBench\Tax\Requests;

use LazyBench\Tax\Http\Client;

/**
 * 获取Token服务
 * Author:Robert
 *
 * Class TokenRequest
 * @package Tax\Requests
 */
class TaskRequest extends BaseRequest implements RequestInterface
{
    /**
     * @var
     */
    public $sourceId;

    /**
     * @var
     */
    public $uuid;

    /**
     * @var
     */
    public $employerUuid;

    /**
     * @var
     */
    public $employerName;

    /**
     * @var
     */
    public $employerIdNo;

    /**
     * @var
     */
    public $hirerUuid;

    /**
     * @var
     */
    public $hirerIdType;

    /**
     * @var
     */
    public $hirerName;

    /**
     * @var
     */
    public $hirerIdNo;

    /**
     * @var
     */
    public $companyName;

    /**
     * @var
     */
    public $companyLicenseNo;

    /**
     * @var
     */
    public $publishDevice;

    /**
     * @var
     */
    public $publishDate;

    /**
     * @var
     */
    public $title;

    /**
     * @var
     */
    public $desc;

    /**
     * @var
     */
    public $fee;

    /**
     * @var
     */
    public $industry;

    /**
     * @var
     */
    public $industryCode;

    /**
     * @var
     */
    public $cityCode;

    /**
     * @var
     */
    public $paymentType;

    /**
     * @var
     */
    public $goodsNo;

    /**
     * @var
     */
    public $startDate;

    /**
     * @var
     */
    public $endDate;

    /**
     * @var
     */
    public $performanceRatio;

    /**
     * @var
     */
    public $totalPrice;

    /**
     * @var
     */
    public $unitPrice;

    /**
     * @var
     */
    public $platformPoundage;

    /**
     * @var
     */
    public $hirerReceiveDate;

    /**
     * @var
     */
    public $hirerReceiveDevice;

    /**
     * @var
     */
    public $paymentBrand;

    /**
     * @var
     */
    public $paymentOrderNo;

    /**
     * @var
     */
    public $paymentAccount;

    /**
     * @var
     */
    public $bankBalanceNo;

    /**
     * @var
     */
    public $hirerReceiveCityCode;

    /**
     * @var
     */
    public $hirerReceiveIp;

    /**
     * @var
     */
    public $hirerReceiveMac;

    /**
     * @var
     */
    public $settleDate;

    /**
     * @var
     */
    public $payeeName;

    /**
     * @var
     */
    public $payeeNameIdNo;

    /**
     * @var
     */
    public $evidenceImage;


    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postDdxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'ddxx';
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->hirerReceiveDate || !$this->hirerReceiveDevice || !$this->paymentBrand || !$this->paymentOrderNo || !$this->paymentAccount || !$this->bankBalanceNo || !$this->hirerReceiveCityCode || !$this->hirerReceiveIp || !$this->settleDate || !$this->payeeName || !$this->payeeNameIdNo || !$this->evidenceImage || !$this->fee || !$this->industry || !$this->industryCode || !$this->cityCode || !$this->paymentType || !$this->startDate || !$this->endDate || !$this->hirerName || !$this->hirerIdNo || !$this->companyName || !$this->companyLicenseNo || !$this->publishDevice || !$this->publishDate || !$this->title || !$this->desc || !$this->sourceId || !$this->uuid || !$this->employerUuid || !$this->employerName || !$this->employerIdNo || !$this->hirerUuid || !$this->hirerIdType) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        if (!strlen($this->totalPrice) || !strlen($this->platformPoundage)) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return true;
    }

    /**
     * 发布方负责人姓名
     * Author:Robert
     *
     * @param string $employerUuid 发布方唯一标识
     * @param string $name 姓名
     * @param string $idNo 身份证号
     */
    public function setEmployer(string $employerUuid, string $name, string $idNo)
    {
        $this->params['fbfuuid'] = $this->employerUuid = $employerUuid;
        $this->params['fbfxm'] = $this->employerName = $name;
        $this->params['fbfsfzjhm'] = $this->employerIdNo = $idNo;
    }

    /**
     * Author:Robert
     *
     * @param string $name 姓名
     * @param string $uuid 对应服务方唯一标识，来自服务方注册接口
     * @param string $idType 证件类型
     * @param string $idNo 证件号
     */
    public function setHirer(string $uuid, string $name, string $idType, string $idNo)
    {
        $this->params['fwfuuid'] = $this->hirerUuid = $uuid;
        $this->params['fwfxm'] = $this->hirerName = $name;
        $this->params['fwfsfzjlx'] = $this->hirerIdType = $idType;
        $this->params['fwfsfzjhm'] = $this->hirerIdNo = $idNo;
    }

    /**
     * 发布方企业信息
     * Author:Robert
     *
     * @param string $name 企业名称
     * @param string $licenseNo 营业执照
     */
    public function setEmployerCompany(string $name, string $licenseNo)
    {
        $this->params['fbfnsrmc'] = $this->companyName = $name;
        $this->params['fbfnsrsbh'] = $this->companyLicenseNo = $licenseNo;
    }


    /**
     * 订单唯一标识
     * Author:Robert
     *
     * @param string $uuid
     * @param string $prefix 如果相关uuid为数字，需要增加平台缩写作为前缀
     */
    public function setUUID(string $uuid, string $prefix = '')
    {
        $this->params['dduuid'] = $this->uuid = $prefix.$uuid;
    }


    /**
     * 任务基本信息
     * Author:Robert
     *
     * @param string $id 订单原始编号
     * @param string $title 任务标题
     * @param string $desc 任务描述
     * @param string $industry 服务所属行业类别 （软件编程、3D制图、平面设计、兼职销售、包装设计等）
     * @param string $industryCode 所属行业编码 参见《行业代码-名称表》
     */
    public function setBasicInfo(string $id, string $title, string $desc, string $industry, string $industryCode)
    {
        $this->params['ddbh'] = $this->sourceId = $id;
        $this->params['hwmc'] = $this->title = $title;
        $this->params['rwms'] = $this->desc = $desc;
        $this->params['hwsshy'] = $this->industryCode = $industryCode;
        $this->params['hwsshylb'] = $this->industry = $industry;
    }

    /**
     * 任务支付信息
     * Author:Robert
     *
     * @param string $paymentBrand 支付方式 银行卡支付、微信支付、支付宝支付、系统内虚拟货币支付
     * @param string $paymentAccount 支付帐号（银行卡号、微信账号、支付宝账号）
     * @param string $paymentEvidence 支付凭证（我理解的是支付的订单编号）
     * @param string $bankBalanceNo 银行流水号
     * @param string $paymentType 支付方式，填（线上支付）
     */
    public function setPayment(string $paymentBrand, string $paymentAccount, string $paymentEvidence, string $bankBalanceNo, string $paymentType = '线上支付')
    {
        $this->params['zffs'] = $this->paymentType = $paymentType;
        $this->params['fwfzffs'] = $this->paymentBrand = $paymentBrand;
        $this->params['yhkh'] = $this->paymentAccount = $paymentAccount;
        $this->params['zfpz'] = $this->paymentOrderNo = $paymentEvidence;
        $this->params['yhls'] = $this->bankBalanceNo = $bankBalanceNo;
    }


    /**
     * 货物商品编码
     * Author:Robert
     *
     * @param string $no 货物商品编码
     */
    public function setGoodsNo(string $no)
    {
        $this->params['hwspbm'] = $this->goodsNo = $no;
    }

    /**
     * 订单任务截图
     * Author:Robert
     *
     * @param string $base64Data
     * @param bool $isPath
     */
    public function setEvidenceImage(string $base64Data, bool $isPath = true)
    {
        if ($isPath && is_readable($base64Data)) {
            $base64Data = Client::imageBase64Encode($base64Data);
        }
        $this->params['ddrwfj'][] = $base64Data;
        $this->evidenceImage = $this->params['ddrwfj'];
    }

    /**
     * 收款人信息
     * Author:Robert
     *
     * @param string $name 姓名
     * @param string $idNo 银行卡
     */
    public function setPayee(string $name, string $idNo)
    {
        $this->params['skrxm'] = $this->payeeName = $name;
        $this->params['skrsfzjhm'] = $this->payeeNameIdNo = $idNo;
    }

    /**
     * 接单信息
     * Author:Robert
     *
     * @param string $date 接单时间
     * @param string $cityCode 订单接收所属区域编号
     * @param string $device 接单设备 接单方式（PC、手机）
     * @param string $ip 服务方电脑ip
     * @param string $mac 服务方电脑mac地址或者手机设备号
     * @throws \Exception
     */
    public function setHirerAcceptInfo(string $date, string $cityCode, string $device, string $ip, string $mac)
    {
        if (!in_array($device, ['PC', 'MOBILE'])) {
            throw new \Exception('发布设备填写不合法');
        }
        $this->params['jdsj'] = $this->hirerReceiveDate = $date;
        $this->params['jdfs'] = $this->hirerReceiveDevice = $device;
        $this->params['ddjsssqy'] = $this->hirerReceiveCityCode = $cityCode;
        $this->params['fwfipdz'] = $this->hirerReceiveIp = $ip;
        $this->params['fwfmacdz'] = $this->hirerReceiveMac = $mac;
    }


    /**
     * Author:Robert
     *
     * @param string $fee 发布金额
     * @param string $unitPrice 服务方结算单价
     * @param string $total 服务方结算金额
     * @param string $platformPoundage 平台服务费
     * @param string $date 订单最终结算时间
     * @param string $performanceRatio 绩效系数(搞不懂是啥)
     */
    public function setSettleInfo(string $fee, string $unitPrice, string $total, string $platformPoundage, string $date, string $performanceRatio = '')
    {
        $this->params['fbfddje'] = $this->fee = $fee;
        $this->params['fwfjsdj'] = $this->unitPrice = $unitPrice;
        $this->params['fwfddje'] = $this->totalPrice = $total;
        $this->params['ptfwf'] = $this->platformPoundage = $platformPoundage;
        $this->params['jxxs'] = $this->performanceRatio = $performanceRatio;
        $this->params['jssj'] = $this->settleDate = $date;
    }


    /**
     * 任务发布信息
     * Author:Robert
     *
     * @param string $publishDevice 发布方式 填写（手机、电脑））
     * @param string $publishDate 发布时间
     * @param string $cityCode 发布地点 参见《行政区划代码-名称表》
     * @throws \Exception
     */
    public function setPublishInfo(string $publishDevice, string $publishDate, string $cityCode)
    {
        if (!in_array($publishDevice, ['PC', 'MOBILE'])) {
            throw new \Exception('发布设备填写不合法');
        }
        $this->params['fdfs'] = $this->publishDevice = $publishDevice;
        $this->params['fdsj'] = $this->publishDate = $publishDate;
        $this->params['fddd'] = $this->cityCode = $cityCode; //发单地点
        $this->params['ddfbssqy'] = $this->cityCode = $cityCode; //订单发布所属区域
    }

    /**
     * 设置任务时间进度信息
     * Author:Robert
     *
     * @param string $startDate 服务方起始服务时间
     * @param string $endDate 服务方结束服务时间
     * @param string $timeUnit 时间单位
     * @throws \Exception
     */
    public function setDuration(string $startDate, string $endDate, string $timeUnit = '小时')
    {
        if (!$startDate || !$endDate) {
            throw new \Exception('设置任务时间进度信息不能为空');
        }
        $this->params['qsfwsj'] = $this->startDate = $startDate;
        $this->params['jsfwsj'] = $this->endDate = $endDate;
        $diff = date_diff(date_create($startDate), date_create($endDate));
        $day = $diff->format('%a');
        $hour = $diff->format('%h');
        $hour = ($day * 24) + $hour;
        $this->params['fwsj'] = $hour != '0' ? (string)$hour : '1';
        $this->params['fwfjsds'] = $timeUnit ? $timeUnit : '小时';
    }
    /**
     * Author:Robert
     *
     * @param CollectionInterface $collection
     * @throws \Exception
     */
    public function addCollection(CollectionInterface $collection)
    {
    }
}
