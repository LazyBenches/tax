<?php

namespace LazyBench\Tax\Claim\Tax;

/**
 * Author:LazyBench
 * Date:2019/1/4
 */

use LazyBench\Tax\Claim\Interfaces\ExportInterface;
use LazyBench\Tax\Claim\Base\ExportBase;

/**
 * Author:LazyBench
 * Date:2019/1/3
 */
class Detail extends ExportBase implements ExportInterface
{
    protected $fileName = '附件3天津万播优歌科技信息咨询有限公司---代征明细表';
    protected $copyFile = 'detail.xls';
    protected $cellKeys = [
        'A' => 'id',//序号
        'B' => 'realName',//姓名
        'C' => 'idCard',//身份证号
        'D' => 'area',//地址
        'E' => 'mobile',//电话
        'F' => 'projectChildCode',//征收品目
        'G' => 'personalWages',//含税金额
        'H' => 'personalTaxBasis',//不含税金额
        'I' => 'addTax',//代征增值税
        'J' => 'addTaxExt',//代征增值税附税
        'K' => 'taxAmount',//税费合计
    ];

    /**
     * Author:LazyBench
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setFree()
    {
        $this->setSheet(0);
    }

    /**
     * Author:LazyBench
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setUnFree()
    {
        $this->setSheet(1);
    }

    /**
     * Author:LazyBench
     *
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setValue($data)
    {
        foreach ($this->cellKeys as $key => $valueKey) {
            if (isset($data[$valueKey])) {
                $this->workSheet->getCell("{$key}{$this->cell}")->setValue($data[$valueKey]." ");
            }
        }
    }

    /**
     * Author:LazyBench
     *
     * @param $dateApply
     * @param $cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setApplyDate($dateApply, $cell)
    {
        $value = "申报日期：{$dateApply}";
        $this->workSheet->getCell("J{$cell}")->setValue($value);
    }

    /**
     * Author:LazyBench
     *
     * @param $dateRange
     * @param $cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setDownLoadDate($dateRange, $cell)
    {
        $value = "所属期：{$dateRange}";
        $this->workSheet->getCell("E{$cell}")->setValue($value);
    }

    /**
     * Author:LazyBench
     *
     * @param $cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setCompanyName($cell)
    {
        $value = '代征单位名称：天津万播优歌科技信息咨询有限公司';
        $this->workSheet->getCell("A{$cell}")->setValue($value);
    }

    /**
     * Author:LazyBench
     *
     * @param $freeData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function createFree($freeData)
    {
        $this->setFree();
        $this->createData($freeData);
    }

    /**
     * Author:LazyBench
     *
     * @param $unFreeData
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function createUnFree($unFreeData)
    {
        $this->setUnFree();
        $this->createData($unFreeData);
    }

    /**
     * Author:LazyBench
     * 设置统计数据
     */
    public function setTotalValue()
    {
    }

    /**
     * Author:LazyBench
     *
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function createData($data)
    {
        foreach ($data as $key => $value) {
            if ($value['idCard']) {
                $idCard = new \IdCard();
                $info = $idCard->getIdCard($value['idCard']);
                $value['area'] = $info['area'] ?? '';
                $value['realName'] = $info['realname'] ?? '';
                $value['mobile'] = $info['mobile'] ?? '';
            }
            $value['projectChildCode'] = 101017302;
            $value['id'] = $this->cell - $this->startCell + 1;
            $this->setCell($this->cell)->setValue($value);
            $this->setCell($this->cell + 1);
        }
    }

    /**
     * Author:LazyBench
     * @param $date
     * @param $page
     * @return array
     * 按页获取免税数据
     */
    public function getFreeData($date, $page)
    {

        $freeData = \UserStatisticsMonth::find([
            'conditions' => 'month = :month: and id_card>0 and is_add=0 and is_filter=0 and tax_wages>0',
            'bind' => [
                'month' => $date,
            ],
            'columns' => 'id_card idCard,tax_basis personalTaxBasis,tax_wages personalWages,tax_basis personalTaxBasis,
             person_add_tax addTax,person_add_tax_ext addTaxExt,0.00 taxAmount',
            'offset' => ($page - 1) * $this->limit,
            'limit' => $this->limit,
            'order' => 'id desc',
        ]);
        return $freeData->toArray();
    }

    /**
     * Author:LazyBench
     * @param $date
     * @param $page
     * @return array
     * 按页获取征税数据
     */
    public function getUnFreeData($date, $page)
    {
        $freeData = \UserStatisticsMonth::find([
            'conditions' => 'month = :month: and id_card>0 and is_add=1 and is_filter=0 and tax_wages>0',
            'bind' => [
                'month' => $date,
            ],
            'columns' => 'id_card idCard,tax_basis personalTaxBasis,tax_wages personalWages,tax_basis personalTaxBasis,
             person_add_tax addTax,person_add_tax_ext addTaxExt,person_add_tax+person_add_tax_ext taxAmount
            ',
            'offset' => ($page - 1) * $this->limit,
            'limit' => $this->limit,
            'order' => 'id desc',
        ]);
        return $freeData->toArray();
    }

    /**
     * Author:LazyBench
     *
     * @param $cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setMark($cell)
    {
        $dateRange = "{$this->startDate}--{$this->endDate}";//'2019.01.03--2019.01.04';
        $this->setDownLoadDate($dateRange, $cell);//所属期
        $this->setApplyDate(date('Y.m.d'), 2);
    }
}
