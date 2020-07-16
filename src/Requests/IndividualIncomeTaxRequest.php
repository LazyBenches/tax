<?php

namespace LazyBench\Tax\Requests;


/**
 * 个人申报信息信息接口
 * Author:Robert
 *
 * Class IndividualIncomeTaxRequest
 * @package Tax\Requests
 */
class IndividualIncomeTaxRequest extends BaseRequest implements RequestInterface
{


    protected $collection = [];


    /**
     * @var
     */
    public $startDate;

    /**
     * @var
     */
    public $endDate;


    /**
     * Author:Robert
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return 'postSbxx0001';
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getNodeName(): string
    {
        return 'sbxx';
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->startDate || !$this->endDate || !$this->collection) {
            $this->setMessage(__CLASS__.'表单填写不完整');
            return false;
        }
        return !$this->hasMessage();
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

    /**
     * Author:Robert
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->collection;
    }
}
