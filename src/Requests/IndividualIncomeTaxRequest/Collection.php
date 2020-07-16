<?php

namespace LazyBench\Tax\Requests\IndividualIncomeTaxRequest;

use LazyBench\Tax\Requests\BaseRequest;
use LazyBench\Tax\Requests\CollectionInterface;

/**
 * Author:Robert
 *
 * Class Collection
 * @package Tax\Requests\IndividualIncomeTaxRequest
 */
class Collection extends BaseRequest implements CollectionInterface
{

    /**
     * @var
     */
    public $taxCategory;

    /**
     * @var
     */
    public $taxTypeCode;

    /**
     * @var
     */
    public $taxBaseTotal;

    /**
     * @var
     */
    public $taxRate;

    /**
     * @var
     */
    public $taxPayer;

    /**
     * @var
     */
    public $taxPayAbleTotal;

    /**
     * @var
     */
    public $taxReducedTotal;

    /**
     * @var
     */
    public $taxReducedType;

    /**
     * @var
     */
    public $taxPaidTotal;

    /**
     * @var
     */
    public $agentTaxChargeAbleTotal;

    /**
     * @var
     */
    public $agentTaxPaidTotal;

    /**
     * @var
     */
    public $taxOriginSign;

    /**
     * @var
     */
    public $taxOriginCode;

    /**
     * @var
     */
    public $taxOriginPosition;

    /**
     * @var
     */
    public $taxPayerCompanyName;

    /**
     * @var
     */
    public $taxPayerIdNo;

    /**
     * @var
     */
    public $taxPayerCompanyLicenseNo;

    /**
     * @var
     */
    public $taxpayerIdTypeCode;

    /**
     * @var
     */
    public $industryCode;

    /**
     * @var
     */
    public $taxpayerCountryNo;

    /**
     * @var
     */
    public $taxpayerUuid;

    /**
     * 增值税
     */
    const ADD_VALUE_TAX_CODE = '10101';

    /**
     * 印花税
     */
    const STAMP_TAX_CODE = '10111';

    /**
     * 城建费
     */
    const CITY_BUILDING_TAX_CODE = '10109';

    /**
     * 教育附加税
     */
    const EDUCATION_ADDITIONAL_TAX_CODE = '30203';

    /**
     * 本地教育附加税
     */
    const LOCAL_EDUCATION_ADDITIONAL_TAX_CODE = '30216';

    const TAX_TYPE_MAP = [
        self::ADD_VALUE_TAX_CODE => '增值税',
        self::STAMP_TAX_CODE => '印花税',
        self::CITY_BUILDING_TAX_CODE => '城建费',
        self::EDUCATION_ADDITIONAL_TAX_CODE => '教育附加税',
        self::LOCAL_EDUCATION_ADDITIONAL_TAX_CODE => '本地教育附加税',
    ];

    /**
     * 税率映射表
     */
    const TAX_TYPE_RATIO_MAP = [
        '0.07' => self::CITY_BUILDING_TAX_CODE,
        '0.03' => self::EDUCATION_ADDITIONAL_TAX_CODE,
        '0.02' => self::LOCAL_EDUCATION_ADDITIONAL_TAX_CODE,
    ];

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->taxPayerIdNo || !$this->taxpayerIdTypeCode || !$this->industryCode || !$this->taxpayerCountryNo || !$this->taxpayerUuid || !$this->taxCategory || !$this->taxTypeCode || !$this->taxPayer) {
            $this->setMessage(__CLASS__.'表单填写不完整'.__LINE__);
            return false;
        }
        if (!strlen($this->agentTaxChargeAbleTotal) || !strlen($this->taxReducedTotal) || !strlen($this->taxRate) || !strlen($this->taxBaseTotal)) {
            $this->setMessage(__CLASS__.'表单填写不完整'.__LINE__);
            return false;
        }
        return true;
    }

    /**
     * 征收项目
     * Author:Robert
     *
     * @param string $taxTypeCode
     * @throws \Exception
     */
    public function setTaxType(string $taxTypeCode)
    {

        if (!in_array($taxTypeCode, array_keys(self::TAX_TYPE_MAP))) {
            throw  new \Exception('不合法的征收项目');
        }
        $this->params['zsxm'] = $this->taxTypeCode = $taxTypeCode;
    }

    /**
     * 征收品目
     * Author:Robert
     *
     * @param string $taxCategory
     */
    public function setTaxCategory(string $taxCategory)
    {
        $this->params['zspm'] = $this->taxCategory = $taxCategory;
    }


    /**
     * 计税依据
     * Author:Robert
     *
     * @param string $total 当月收入/1.03>=10万（无税时计税依据=当月收入/1.03，有税时计税依据=当月收入/1.03）
     */
    public function setTaxBaseTotal(string $total)
    {
        $this->params['jsyj'] = $this->taxBaseTotal = $total;
    }

    /**
     * 税率或税额
     * Author:Robert
     *
     * @param string $ratio 按各税种执行：增值税3%
     * 运输印花税万分之五、技术印花税万分之三（印花税跟局方确认）
     * 城建税7%
     * 教育费附加3%
     * 地方教育附加 2%\
     */
    public function setTaxRate(string $ratio)
    {
        $this->params['slhse'] = $this->taxRate = $ratio;
    }

    /**
     * 应纳税额
     * Author:Robert
     *
     * @param $total
     */
    public function setTaxPayAbleTotal(string $total)
    {
        $this->params['ynse'] = $this->taxPayAbleTotal = $total;
    }

    /**
     * 减免额度
     * Author:Robert
     *
     * @param string $total 有税申报时：减免税额=0，无税申报时：减免税额 = 应纳税额
     * @param string $type 减免性质（减免代码）
     */
    public function setTaxReducedTotal(string $total, string $type = '')
    {
        $this->params['jmse'] = $this->taxReducedTotal = $total;
        $this->params['jmxz'] = $this->taxReducedType = $type;
    }

    /**
     * 已缴税额
     * Author:Robert
     *
     * @param string $total 一般为0
     */
    public function setTaxPaidTotal(string $total)
    {
        $this->params['yjse'] = $this->taxPaidTotal = $total;
    }

    /**
     * 应代征税额
     * Author:Robert
     *
     * @param string $total
     */
    public function setAgentTaxChargeAbleTotal(string $total)
    {
        $this->params['ydzse'] = $this->agentTaxChargeAbleTotal = $total;
    }

    /**
     * 已代征税额
     * Author:Robert
     *
     * @param string $total 一般为0
     */
    public function setAgentTaxPaidTotal(string $total)
    {
        $this->params['sdzse'] = $this->agentTaxPaidTotal = $total;
    }

    /**
     * 税源标志
     * Author:Robert
     *
     * @param string $sign 税源标志
     * @param string $code 税源编号
     * @param string $position 税源坐落
     */
    public function setTaxOrigin(string $sign, string $code, string $position)
    {
        $this->params['sybz'] = $this->taxOriginSign = $sign;
        $this->params['sybh'] = $this->taxOriginCode = $code;
        $this->params['syzl'] = $this->taxOriginPosition = $position;
    }

    /**
     * 服务方唯一标识
     * Author:Robert
     *
     * @param string $uuid
     */
    public function setTaxpayerUUId(string $uuid)
    {
        $this->params['fwfuuid'] = $this->taxpayerUuid = $uuid;
    }


    /**
     * TODO 有问题
     * 被代征单位纳税人信息
     * Author:Robert
     *
     * @param string $name 纳税人名称（平台企业）
     * @param string $companyLicenseNo 纳税人识别号（平台企业）
     */
    public function setTaxpayerCompany(string $name, string $companyLicenseNo = '')
    {
        $this->params['nsrmc'] = $this->taxPayerCompanyName = $name;
        $this->params['nsrsbh '] = $this->taxPayerCompanyLicenseNo = $companyLicenseNo;
    }

    /**
     * 被代征人信息
     * Author:Robert
     *
     * @param string $name 被代征人
     * @param string $idNo 被代征人的身份证号
     * @param string $idTypeCode 被代征人证件类型
     * @param string $countryNo 国家或地区-填写被代征人国家地区代码，参见《申报表-计税excel页签：国家和地区代码》
     */
    public function setTaxpayer(string $name, string $idNo, string $idTypeCode = '201', string $countryNo = '156')
    {
        $this->params['zjhm'] = $this->taxPayerIdNo = $idNo;
        $this->params['bdzdwnsrsbh'] = $this->taxPayerIdNo = $idNo;
        $this->params['bdzdwnsrmc'] = $this->taxPayer = $name;
        $this->params['zjlx'] = $this->taxpayerIdTypeCode = $idTypeCode;
        $this->params['gjhdq'] = $this->taxpayerCountryNo = $countryNo;
    }

    /**
     * 所属行业
     * Author:Robert
     *
     * @param string $industryCode （-填写平台企业所在行业代码，参见《行业代码-名称表》）
     */
    public function setTaxIndustryCode(string $industryCode)
    {
        $this->params['sshy'] = $this->industryCode = $industryCode;
    }


    /**
     * Author:Robert
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->params;
    }
}
