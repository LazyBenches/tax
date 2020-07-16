<?php

namespace LazyBench\Tax\Requests;

/**
 * 平台企业基础信息接口
 * Author:Robert
 *
 * Class PlatformInformationRequest
 * @package Tax\Requests
 */
class PlatformInformationRequest extends BaseRequest implements RequestInterface
{


    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $licenseNo;

    /**
     * @var
     */
    public $industryCode;

    /**
     * @var
     */
    public $revenueDepartment;

    /**
     * @var
     */
    public $revenueParentDepartment;

    /**
     * @var
     */
    public $registerCapital;

    /**
     * @var
     */
    public $financialPersonMail;

    /**
     * @var
     */
    public $status;

    /**
     * @var
     */
    public $employeeNumber;

    /**
     * @var
     */
    public $businessScope;

    /**
     * @var
     */
    public $legalPersonIdNo;

    /**
     * @var
     */
    public $legalPersonMail;

    /**
     * @var
     */
    public $taxAgentPerson;

    /**
     * @var
     */
    public $siteUrl;

    /**
     * @var
     */
    public $legalPersonTel;

    /**
     * @var
     */
    public $legalPersonMobile;


    /**
     * @var
     */
    public $address;

    /**
     * @var
     */
    public $legalPerson;

    /**
     * @var
     */
    public $financialPersonName;

    /**
     * @var
     */
    public $registerAddressCode;

    /**
     * @var
     */
    public $businessAddressCode;

    /**
     * @var
     */
    public $businessAddress;

    /**
     * @var
     */
    public $legalPersonIdType;

    /**
     * @var
     */
    public $financialPersonIdNo;

    /**
     * @var
     */
    public $financialPersonIdType;

    /**
     * @var
     */
    public $englishName;

    /**
     * @var
     */
    public $financialPersonTel;

    /**
     * @var
     */
    public $financialPersonMobile;


    /**
     * @var
     */
    public $street;

    /**
     * @var
     */
    public $taxPersonName;

    /**
     * @var
     */
    public $taxPersonIdType;

    /**
     * @var
     */
    public $taxPersonIdNo;

    /**
     * @var
     */
    public $taxPersonTel;

    /**
     * @var
     */
    public $taxPersonMobile;

    /**
     * @var
     */
    public $taxPersonMail;

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postPtxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'ptjbxx';
    }


    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->name || !$this->licenseNo || !$this->industryCode || !$this->revenueDepartment || !$this->revenueParentDepartment || !$this->registerCapital || !$this->financialPersonMail || !$this->status || !$this->employeeNumber || !$this->businessScope || !$this->legalPersonIdNo || !$this->legalPersonMail || !$this->siteUrl || !$this->legalPersonTel || !$this->legalPersonMobile || !$this->address || !$this->legalPerson || !$this->financialPersonName || !$this->registerAddressCode || !$this->legalPersonIdType || !$this->financialPersonIdNo || !$this->financialPersonIdType || !$this->financialPersonTel || !$this->financialPersonMobile || !$this->street || !$this->taxPersonName || !$this->taxPersonIdType || !$this->taxPersonIdNo || !$this->taxPersonTel || !$this->taxPersonMobile || !$this->taxPersonMail) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return true;
    }

    /**
     * 设置纳税人（企业）名称
     *
     * @param string $name 姓名
     * @param string $englishName 英文名
     */
    public function setCompanyName(string $name, string $englishName = '')
    {
        $this->params['nsrmc'] = $this->name = $name;
        $this->params['nsrywmc'] = $this->englishName = $englishName;
    }

    /**
     * 设置注册资本
     * Author:Robert
     *
     * @param string $capital
     */
    public function setCompanyRegisterCapital(string $capital)
    {
        $this->params['zczb'] = $this->registerCapital = $capital;
    }

    /**
     * 设置纳税人识别号（营业执照）
     * @param string $licenseNo
     */
    public function setCompanyLicenseNo(string $licenseNo)
    {
        $this->params['nsrsbh'] = $this->licenseNo = $licenseNo;
    }

    /**
     * 设置行业编码（营业执照）
     *
     * @param string $industryCode
     */
    public function setCompanyIndustryCode(string $industryCode)
    {
        $this->params['hy'] = $this->industryCode = $industryCode;
    }

    /**
     * 设置主管税务机关
     * Author:Robert
     *
     * @param string $department 主管税务所
     * @param string $parentDepartment 主管税务局
     */
    public function setRevenue(string $department, string $parentDepartment)
    {
        $this->params['zgswskfj'] = $this->revenueDepartment = $department;
        $this->params['zgswjg'] = $this->revenueParentDepartment = $parentDepartment;
    }

    /**
     * Author:Robert
     *
     * @param string $status 纳税人状态
     */
    public function setCompanyStatus(string $status)
    {
        $this->params['nsrzt'] = $this->status = $status;
    }

    /**
     * 注册地址
     * Author:Robert
     *
     * @param string $address 注册地址
     * @param string $street 街道乡镇
     * @param string $cityCode 注册地址行政区划
     */
    public function setCompanyRegisterAddress(string $address, string $street, string $cityCode)
    {
        $this->params['zcdz'] = $this->address = $address;
        $this->params['jdxz'] = $this->street = $street;
        $this->params['zcdzxzqh'] = $this->registerAddressCode = $cityCode;
    }

    /**
     * Author:Robert
     *
     * @param string $address
     * @param string $cityCode 生产经营地址行政区划
     */
    public function setCompanyBusinessAddress(string $address, string $cityCode)
    {
        $this->params['scjydz'] = $this->businessAddress = $address;
        $this->params['scjydzxzqh'] = $this->businessAddressCode = $cityCode;
    }

    /**
     * 设置法定代表人
     * Author:Robert
     *
     * @param string $name 姓名
     * @param string $idType 身份证件类型(身份证:201)
     * @param string $idNo 证件号码
     * @param string $tel 固定电话
     * @param string $mobile 移动电话
     * @param string $mail 邮箱
     */
    public function setCompanyLegalPerson(string $name, string $idType, string $idNo, string $tel, string $mobile, string $mail)
    {
        $this->params['fddbrxm'] = $this->legalPerson = $name;
        $this->params['fddbrsfzjhm'] = $this->legalPersonIdNo = $idNo;
        $this->params['fddbrsfzjzl'] = $this->legalPersonIdType = $idType;
        $this->params['fddbrdzxx'] = $this->legalPersonMail = $mail;
        $this->params['fddbrgddh'] = $this->legalPersonTel = $tel;
        $this->params['fddbryddh'] = $this->legalPersonMobile = $mobile;
    }


    /**
     * 设置财务人员信息
     * Author:Robert
     *
     * @param string financialPersonName 姓名
     * @param string $idType 证件类型
     * @param string $idNo 证件号码
     * @param string $tel 固定电话
     * @param string $mobile 移动电话
     * @param string $mail 邮箱
     */
    public function setCompanyFinancialPerson(string $name, string $idType, string $idNo, string $tel, string $mobile, string $mail)
    {
        $this->params['cwfzrxm'] = $this->financialPersonName = $name;
        $this->params['cwfzrsfzjhm'] = $this->financialPersonIdNo = $idNo;
        $this->params['cwfzrsfzjzl'] = $this->financialPersonIdType = $idType;
        $this->params['cwfzrgddh'] = $this->financialPersonTel = $tel;
        $this->params['cwfzryddh'] = $this->financialPersonMobile = $mobile;
        $this->params['cwfzrdzxx'] = $this->financialPersonMail = $mail;
    }


    /**
     * 设置办税人员信息
     * Author:Robert
     *
     * @param string $name 姓名
     * @param string $idType 证件类型
     * @param string $idNo 证件号码
     * @param string $tel 固定电话
     * @param string $mobile 移动电话
     * @param string $mail 邮箱
     */
    public function setCompanyTaxPerson(string $name, string $idType, string $idNo, string $tel, string $mobile, string $mail)
    {
        $this->params['bsrxm'] = $this->taxPersonName = $name;
        $this->params['bsrsfzjzl'] = $this->taxPersonIdType = $idType;
        $this->params['bsrsfzjhm'] = $this->taxPersonIdNo = $idNo;
        $this->params['bsrgddh'] = $this->taxPersonTel = $tel;
        $this->params['bsryddh'] = $this->taxPersonMobile = $mobile;
        $this->params['bsrdzxx'] = $this->taxPersonMail = $mail;
    }

    /**
     * 设置税务代理人
     * Author:Robert
     *
     * @param string $name 姓名
     */
    public function setCompanyTaxAgentPerson(string $name)
    {
        $this->params['swdlrmc'] = $this->taxAgentPerson = $name;
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

    /**
     * 从业人数
     * Author:Robert
     *
     * @param string $quantity
     */
    public function setCompanyEmployeeNumber(string $quantity)
    {
        $this->params['cyrs'] = $this->employeeNumber = $quantity;
    }

    /**
     * 设置网址
     * Author:Robert
     *
     * @param string $url
     */
    public function setPlatformUrl(string $url)
    {
        $this->params['wz'] = $this->siteUrl = $url;
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
