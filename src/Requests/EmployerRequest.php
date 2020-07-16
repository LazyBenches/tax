<?php

namespace LazyBench\Tax\Requests;

use LazyBench\Tax\Http\Client;

/**
 * 发布方
 * Author:Robert
 *
 * Class EmployerRequest
 * @package Tax\Requests
 */
class EmployerRequest extends BaseRequest implements RequestInterface
{

    /**
     * @var
     */
    public $uuid;

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
    public $name;

    /**
     * @var
     */
    public $countryCode;

    /**
     * @var
     */
    public $investor;

    /**
     * @var
     */
    public $licenceNo;

    /**
     * @var
     */
    public $companyName;

    /**
     * @var
     */
    public $companyAddress;

    /**
     * @var
     */
    public $companyAddressDetail;

    /**
     * @var
     */
    public $legalPersonName;

    /**
     * @var
     */
    public $registerCapital;

    /**
     * @var
     */
    public $registerDate;

    /**
     * @var
     */
    public $cityCode;

    /**
     * @var
     */
    public $businessScope;

    /**
     * @var
     */
    public $revenueDepartment;

    /**
     * @var
     */
    public $platFormRegisterDate;

    /**
     * @var
     */
    public $companyType;

    /**
     * @var
     */
    public $licenceImg;

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postFbfxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'fbfjbxx';
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!in_array($this->companyType, ['0', '1',])) {
            $this->setMessage('companyType应该为0或者1的字符');
            return false;
        }
        if (!$this->uuid || !$this->idNo || !$this->mobile || !$this->name || !$this->countryCode || !$this->licenceNo || !$this->companyName || !$this->companyAddress || !$this->companyAddressDetail || !$this->legalPersonName || !$this->registerCapital || !$this->registerDate || !$this->cityCode || !$this->businessScope || !$this->revenueDepartment || !$this->platFormRegisterDate || !$this->licenceImg) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return true;
    }

    /**
     * 设置营业执照
     * Author:Robert
     *
     * @param string $no 统一社会信用代码
     * @param string $imageBase64 "data:image/jpg;base64,编码" 营业执照图片
     * @param bool $isPath
     */
    public function setCompanyLicenceNo(string $no, string $imageBase64, bool $isPath = true)
    {
        if ($isPath && is_readable($imageBase64)) {
            $imageBase64 = Client::imageBase64Encode($imageBase64);
        }
        $this->params['nsrsbh'] = $this->licenceNo = $no;
        $this->params['yyzzfj'][] = $imageBase64;
        $this->licenceImg = $this->params['yyzzfj'];
    }

    /**
     * 发布方唯一标识
     * Author:Robert
     *
     * @param string $uuid
     */
    public function setPlatformUUId(string $uuid)
    {
        $this->params['fbfuuid'] = $this->uuid = $uuid;
    }

    /**
     * 投资方信息
     * Author:Robert
     *
     * @param string $info
     */
    public function setCompanyInvestor(string $info)
    {
        $this->params['tzfxx'] = $this->investor = $info;
    }


    /**
     * 企业名称
     * Author:Robert
     *
     * @param string $name
     */
    public function setCompanyName(string $name)
    {
        $this->params['nsrmc'] = $this->companyName = $name;
    }

    /**
     * 设置企业地址
     * Author:Robert
     *
     * @param string $address
     * @param string $addressDetail
     * @param string $countryCode 国家地区代码
     * @param string $cityCode 填写行政区划代码（市级）
     */
    public function setCompanyAddress(string $address, string $addressDetail, string $countryCode, string $cityCode)
    {
        $this->params['gsdz'] = $this->companyAddress = $address;
        $this->params['xxdz'] = $this->companyAddressDetail = $addressDetail;
        $this->params['gjdq'] = $this->countryCode = $countryCode;
        $this->params['ssdq'] = $this->cityCode = $cityCode;
    }


    /**
     * 设置法人
     * Author:Robert
     *
     * @param string $name
     */
    public function setCompanyLegalPerson(string $name)
    {
        $this->params['fddbr'] = $this->legalPersonName = $name;
    }

    /**
     * 注册资本
     * Author:Robert
     *
     * @param string $capital
     */
    public function setCompanyRegisterCapital(string $capital)
    {
        $this->params['zczb'] = $this->registerCapital = $capital;
    }

    /**
     * 成立日期
     * Author:Robert
     *
     * @param string $date
     */
    public function setCompanyRegisterDate(string $date)
    {
        $this->params['slrq'] = $this->registerDate = $date;
    }

    /**
     * 平台注册时间
     * Author:Robert
     *
     * @param string $date
     */
    public function setPlatformRegisterDate(string $date)
    {
        $this->params['ptzcsj'] = $this->platFormRegisterDate = $date;
    }

    /**
     * 设置企业类型
     * Author:Robert
     *
     * @param string $type （0:一般人/1:小规模）
     */
    public function setCompanyType(string $type)
    {
        $this->params['nsrlx'] = $this->companyType = $type;
    }

    /**
     * 设置主管税务机关
     * Author:Robert
     *
     * @param string $department 主管税务所
     */
    public function setRevenue(string $department)
    {
        $this->params['zgswjg'] = $this->revenueDepartment = $department;
    }


    /**
     * 发布方负责人
     * Author:Robert
     *
     * @param string $name 发布方负责人
     * @param string $mobile 发布方负责人手机号
     * @param string $no 发布方负责人身份证件号
     */
    public function setPlatformPublisher(string $name, string $mobile, string $no)
    {
        $this->params['fbfxm'] = $this->name = $name;
        $this->params['yddh'] = $this->mobile = $mobile;
        $this->params['fbfsfzjhm'] = $this->idNo = $no;
    }


    /**
     * 经营范围
     * Author:Robert
     *
     * @param string $scope
     */
    public function setCompanyBusinessScope(string $scope)
    {
        $this->params['jyfw'] = $this->businessScope = $scope;
    }
}
