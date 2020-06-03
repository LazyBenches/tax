<?php

namespace LazyBench\Tax\Claim\Tax;

use LazyBench\Tax\Claim\Base\ExportBase;
use LazyBench\Tax\Claim\Interfaces\ExportInterface;

/**
 * Author:LazyBench
 * Date:2019/1/7
 */
class Free extends ExportBase implements ExportInterface
{
    protected $copyFile = 'free.xls';
    protected $fileName = '天津万播优歌科技信息咨询有限公司-免税';
    protected $exportMode = true;

    /**
     * Author:LazyBench
     *
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setValue($data)
    {
        isset($data['realName']) && $this->workSheet->getCell("B{$this->cell}")->setValue($data['realName']);
        $this->workSheet->getCell("E{$this->cell}")->setValue(156);
        $this->workSheet->getCell("F{$this->cell}")->setValue(201);
        isset($data['idCard']) && $this->workSheet->getCell("G{$this->cell}")->setValue($data['idCard'].' ');
        $this->workSheet->getCell("H{$this->cell}")->setValue(7519);
        isset($data['projectCode']) && $this->workSheet->getCell("I{$this->cell}")->setValue($data['projectCode']);
        isset($data['itemCode']) && $this->workSheet->getCell("J{$this->cell}")->setValue($data['itemCode']);
        $this->workSheet->getCell("L{$this->cell}")->setValue($this->startDate);
        $this->workSheet->getCell("M{$this->cell}")->setValue($this->endDate);
        isset($data['personalTaxBasis']) && $this->workSheet->getCell("N{$this->cell}")
                                                            ->setValue($data['personalTaxBasis']);
        isset($data['rate']) && $this->workSheet->getCell("O{$this->cell}")->setValue($data['rate']);
        isset($data['addedValueTaxReduction']) && $this->workSheet->getCell("P{$this->cell}")
                                                                  ->setValue($data['addedValueTaxReduction']);
        isset($data['addedValueTaxReduction']) && $this->workSheet->getCell("Q{$this->cell}")
                                                                  ->setValue($data['addedValueTaxReduction']);
        $this->workSheet->getCell("S{$this->cell}")->setValue("=P{$this->cell}-Q{$this->cell}");
        isset($data['reductionType']) && $this->workSheet->getCell("X{$this->cell}")->setValue($data['reductionType']);
    }

    /**
     * Author:LazyBench
     * @param $cell
     * @return $this
     * 设置行
     */
    public function setCell($cell)
    {
        $this->cell = $cell;
        return $this;
    }

    /**
     * Author:LazyBench
     *
     * @param $data
     * @param bool $exportMode
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function createData($data, $exportMode = true)
    {
        $return = [];
        foreach ($data as $index => $value) {
            $value['rate'] = 0.03;
            $value['taxAmount'] = bcmul($value['personalTaxBasis'], $value['rate']);
            $value['projectCode'] = 10101;
            $value['itemCode'] = 101017302;
            $value['reductionType'] = '0001042804';
            $idCard = new \IdCard();
            $card = $idCard->getIdCard($value['idCard']);
            $value['realName'] = $card['realname'] ?? '';
            if ($exportMode) {
                $this->setCell($this->cell)->setValue($value);
                $this->setCell($this->cell + 1);
            } else {
                $return[$index] = $value;
            }
            $value['personalTaxBasis'] = 0.00;
            $value['taxAmount'] = 0.00;
            unset($value['addedValueTaxReduction'], $value['reductionType']);
            foreach ($this->rate as $key => $rate) {
                $value['projectCode'] = $this->projectCode[$key];
                $value['itemCode'] = $this->projectChildCode[$key];
                $value['rate'] = $rate;
                if ($exportMode) {
                    $this->setCell($this->cell)->setValue($value);
                    $this->setCell($this->cell + 1);
                } else {
                    $return[$index]['type'][] = $value;
                }
            }
            if (!$exportMode) {
                return $return;
            }
        }
    }

    /**
     * Author:Robert
     *
     * @param $month
     * @param $page
     * @param string|null $idNo 设置只导出指定某条
     * @return array|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\Model\ResultSetInterface|\UserStatisticsMonth|\UserStatisticsMonth[]
     */
    public function getData($month, $page, string $idNo = null)
    {
        $criteria = [
            'conditions' => 'month = :month: AND id_card >0 AND is_add=0 AND is_filter=0 AND tax_wages>0',
            'bind' => [
                'month' => $month,
            ],
            'offset' => ($page - 1) * $this->limit,
            'limit' => $this->limit,
            'order' => 'id asc',
            'columns' => 'id,id_card idCard,tax_basis personalTaxBasis,person_add_tax addedValueTax,person_add_taxing addedValueTaxReduction',
        ];
        if ($idNo) {
            $criteria['conditions'] .= ' AND id_card=:id_card:';
            $criteria['bind']['id_card'] = $idNo;
        }
        $freeData = \UserStatisticsMonth::find($criteria);
        return $freeData->toArray();
    }

    public function setStartDate($date)
    {
        $this->startDate = $date;
    }

    public function setEndDate($date)
    {
        $this->endDate = $date;
    }

    public function setFillDate($date)
    {
        $this->fillDate = $date;
    }

    /**
     * Author:LazyBench
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setMark()
    {
        $this->setSheet(0);
        $this->setCell(4);
        $this->workSheet->getCell("B{$this->cell}")->setValue($this->startDate);
        $this->workSheet->getCell("E{$this->cell}")->setValue($this->endDate);
    }
}
