<?php

namespace LazyBench\Tax\Requests;

use LazyBench\Tax\Http\Client;

/**
 * 服务方
 * Author:Robert
 *
 * Class HirerRequest
 * @package Tax\Requests
 */
class HirerRequest extends BaseRequest implements RequestInterface
{

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
    public $companyAddress;


    /**
     * @var
     */
    public $uuid;

    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $idType;

    /**
     * @var
     */
    public $idNo;

    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $gender;

    /**
     * @var
     */
    public $address;

    /**
     * @var
     */
    public $businessScope;

    /**
     * @var
     */
    public $registerDate;

    /**
     * @var
     */
    public $birthday;

    /**
     * @var
     */
    public $bankName;

    /**
     * @var
     */
    public $holder;

    /**
     * @var
     */
    public $holderIdNo;


    /**
     * @var
     */
    public $bankCardNo;

    /**
     * @var
     */
    public $certificateName;

    /**
     * @var
     */
    public $certificateNo;

    /**
     * @var
     */
    public $ip;

    /**
     * @var
     */
    public $addressCityCode;

    /**
     * @var
     */
    public $mac;

    /**
     * @var
     */
    public $school;

    /**
     * @var
     */
    public $degree;

    /**
     * @var
     */
    public $degreeCertNo;

    /**
     * @var
     */
    public $graduationDate;

    /**
     * @var
     */
    public $idCardImage;


    /**
     * @var
     */
    public $occupation;

    /**
     *
     */
    public const FEMALE_GENDER = 'FEMALE';

    /**
     *
     */
    public const MALE_GENDER = 'MALE';

    /**
     *
     */
    public const GENDER_MAP = [
        self::MALE_GENDER => '男',
        self::FEMALE_GENDER => '女',
    ];


    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postFwfxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'fwfjbxx';
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->uuid || !$this->name || !$this->idType || !$this->idNo || !$this->mobile || !$this->gender || !$this->address || !$this->businessScope || !$this->registerDate || !$this->birthday || !$this->bankName || !$this->holder || !$this->holderIdNo || !$this->bankCardNo || !$this->ip || !$this->addressCityCode || !$this->idCardImage || !$this->birthday || !$this->bankName || !$this->holder || !$this->holderIdNo || !$this->bankCardNo || !$this->ip) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return true;
    }

    /**
     * 注册会员所在企业信息
     * Author:Robert
     *
     * @param string $name 公司名称
     * @param string $licenseNo 纳税人识别号
     * @param string $address 公司地址
     */
    public function setCompany(string $name, string $licenseNo, string $address)
    {
        $this->params['nsrmc'] = $this->companyName = $name;
        $this->params['nsrsbh'] = $this->companyLicenseNo = $licenseNo;
        $this->params['gsdz'] = $this->companyAddress = $address;
    }

    /**
     * 服务方唯一标识
     * Author:Robert
     *
     * @param string $uuid
     */
    public function setUUId(string $uuid)
    {
        $this->params['fwfuuid'] = $this->uuid = $uuid;
    }

    /**
     * 服务方（注册会员）姓名
     * Author:Robert
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->params['fwfxm'] = $this->name = $name;
    }

    /**
     * 服务方出生日期
     * Author:Robert
     *
     * @param string $day
     */
    public function setBirthday(string $day)
    {
        $this->params['fwfcsrq'] = $this->birthday = $day;
    }

    /**
     * 设置银行卡信息
     * Author:Robert
     *
     * @param string $holder 开户人姓名
     * @param string $holderIdNo 开户人身份证号码
     * @param string $bankName 开户银行
     * @param string $bankCardNo 开户银行卡号
     */
    public function setBankCard(string $holder, string $holderIdNo, string $bankName, string $bankCardNo)
    {
        $this->params['khyh'] = $this->bankName = $bankName;
        $this->params['khrxm'] = $this->holder = $holder;
        $this->params['khrsfzjhm'] = $this->holderIdNo = $holderIdNo;
        $this->params['skzh'] = $this->bankCardNo = $bankCardNo;
    }

    /**
     * 资格证书名称
     * Author:Robert
     *
     * @param array $cert [['no'=>'CCNP-82382032840','name'=>'CCNP']]
     */
    public function setCertificate(array $cert = [])
    {
        if ($cert) {
            $this->params['zgzsbh'] = $this->certificateNo = implode(',', array_column($cert, 'no'));
            $this->params['zgzsmc'] = $this->certificateName = implode(',', array_column($cert, 'name'));
        }
    }

    /**
     * 毕业院校（最高学历院校）
     * Author:Robert
     *
     * @param string $school
     * @param string $graduationDate
     * @param string $degree
     * @param string $certNo
     */
    public function setEducation(string $school, string $graduationDate, string $degree, string $certNo)
    {
        $this->params['byyx'] = $this->school = $school;
        $this->params['bysj'] = $this->graduationDate = $graduationDate;
        $this->params['zgxlmc'] = $this->degree = $degree;
        $this->params['xlzsbh'] = $this->degreeCertNo = $certNo;
    }

    /**
     * 职务/职业/级别
     * Author:Robert
     *
     * @param string $position
     */
    public function setOccupation(string $position)
    {
        $this->params['zwjb'] = $this->occupation = $position;
    }

    /**
     * 设置实名认证照片
     * Author:Robert
     *
     * @param string $base64Data
     * @param bool $isPath
     */
    public function setCertification(string $base64Data, bool $isPath = true)
    {
        if ($isPath && is_readable($base64Data)) {
            $base64Data = Client::imageBase64Encode($base64Data);
        }
        $this->params['smrzfj'][] = $this->idCardImage = $base64Data;
        $this->idCardImage = $this->params['smrzfj'];
    }

    /**
     * 服务方电脑
     * Author:Robert
     *
     * @param string $ip
     */
    public function setRegisterIp(string $ip)
    {
        $this->params['fwfipdz'] = $this->ip = $ip;
    }

    /**
     * 服务方电脑mac地址或者手机设备号
     * Author:Robert
     *
     * @param string $mac
     */
    public function setRegisterMac(string $mac)
    {
        $this->params['fwfmacdz'] = $this->mac = $mac;
    }


    /**
     * 设置性别
     * Author:Robert
     *
     * @param string $gender
     * @throws \Exception
     */
    public function setGender(string $gender)
    {
        if ($gender && !in_array($gender, array_keys(self::GENDER_MAP))) {
            throw  new \Exception('错误的性别类型');
        }
        $this->params['fwfxb'] = $this->gender = self::GENDER_MAP[$gender] ?? '';
    }

    /**
     * 设置证件
     * Author:Robert
     *
     * @param string $idNo
     * @param string $idType
     */
    public function setIdCard(string $idNo, string $idType)
    {
        $this->params['fwfsfzjlx'] = $this->idType = $idType;
        $this->params['fwfsfzjhm'] = $this->idNo = $idNo;
    }

    /**
     * 设置手机号码
     * Author:Robert
     *
     * @param string $mobile
     */
    public function setMobile(string $mobile)
    {
        $this->params['yddh'] = $this->mobile = $mobile;
    }

    /**
     * 设置常住地址
     * Author:Robert
     *
     * @param string $address
     * @param string $cityCode 服务方所属地区
     */
    public function setAddress(string $address, string $cityCode)
    {
        $this->params['czdq'] = $this->address = $address;
        $this->params['ssdq'] = $this->addressCityCode = $cityCode;
    }

    /**
     * 设置提供的业务类型
     * Author:Robert
     *
     * @param array $scope 主要提供的业务类别（可多选，类别例如软件编程、3D制图、平面设计、兼职销售、包装设计等等等）文字描述
     */
    public function setBusinessScope(array $scope)
    {
        $this->params['zytgywlb'] = $this->businessScope = implode(',', $scope);
    }

    /**
     * 服务方在平台注册时间
     * Author:Robert
     *
     * @param string $date
     */
    public function registerDate(string $date)
    {
        $this->params['ptzcsj'] = $this->registerDate = $date;
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
