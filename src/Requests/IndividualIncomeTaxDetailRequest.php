<?php

namespace LazyBench\Tax\Requests;


/**
 * 个人所得税详细信息接口
 * Author:Robert
 *
 * Class IndividualIncomeTaxSummaryRequest
 * @package Tax\Requests
 */
class IndividualIncomeTaxDetailRequest extends BaseRequest implements RequestInterface
{

    protected $collection;

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
    public $reportDate;

    /**
     * @var
     */
    public $platformName;

    /**
     * @var
     */
    public $platformLicenseNo;

    /**
     * @var
     */
    public $platformIndustryCode;

    /**
     * @var
     */
    public $platformCityCode;

    /**
     * @var
     */
    public $platformArea;

    /**
     * @var
     */
    public $revenueDepartment;

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->startDate || !$this->endDate || !$this->reportDate || !$this->platformName || !$this->platformLicenseNo || !$this->platformIndustryCode || !$this->platformCityCode || !$this->platformArea || !$this->revenueDepartment || !$this->collection) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return !$this->hasMessage();
    }

    /**
     * Author:Robert
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->collection;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postGrsdsxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'sdsxx';
    }


    /**
     * 税款所属起止
     * Author:Robert
     *
     * @param string $startDate
     * @param string $endDate
     */
    public function setTaxDateRange(string $startDate, string $endDate)
    {
        $this->params['skssqq'] = $this->startDate = $startDate;
        $this->params['skssqz'] = $this->endDate = $endDate;
    }

    /**
     * 申报日期
     * Author:Robert
     *
     * @param string $date
     */
    public function setReportDate(string $date)
    {
        $this->params['sbrq'] = $this->reportDate = $date;
    }


    /**
     * 扣缴义务人(平台企业)信息
     * Author:Robert
     *
     * @param string $name 扣缴义务人识别号（社会信用代码）（平台企业）
     * @param string $licenseNo 扣缴义务人名称（平台企业）
     * @param string string $industryCode 设置行业编码（营业执照）
     * @param string $cityCode （平台企业）-填写行政区划代码（市级），参见《行政区划代码-名称表》
     * @param string $area 街道乡镇
     */
    public function setPlatformCompany(string $name, string $licenseNo, string $industryCode, string $cityCode, string $area)
    {
        $this->params['nsrmc'] = $this->platformName = $name;
        $this->params['nsrsbh'] = $this->platformLicenseNo = $licenseNo;
        $this->params['hy'] = $this->platformIndustryCode = $industryCode;
        $this->params['xzqh'] = $this->platformCityCode = $cityCode;
        $this->params['jdxz'] = $this->platformArea = $area;
    }


    /**
     * 设置主管税务机关
     * Author:Robert
     *
     * @param string $department 主管税务所
     */
    public function setRevenue(string $department)
    {
        $this->params['swjg'] = $this->revenueDepartment = $department;
    }

    /**
     * Author:Robert
     *
     * @param CollectionInterface $collection
     * @throws \Exception
     */
    public function addCollection(CollectionInterface $collection)
    {
        if ($collection->validate() === false) {
            $this->setMessage($collection->getMessage());
        }
        $this->collection[] = array_merge($this->params, $collection->getBody());
    }
}
