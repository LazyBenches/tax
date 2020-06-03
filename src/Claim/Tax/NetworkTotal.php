<?php
/**
 * Author:LazyBench
 * Date:2019/1/25
 */

namespace LazyBench\Tax\Claim\Tax;

use LazyBench\Tax\Claim\Base\ExportBase;
use LazyBench\Tax\Claim\Interfaces\ExportInterface;
use LazyBench\Tax\Constant\Tax;

/**
 * Author:LazyBench
 * Date:2019/1/9
 */
class NetworkTotal extends ExportBase implements ExportInterface
{
    protected $copyFile = 'network_count.xls';
    protected $fileName = '网络平台企业个人所得税扣缴扣缴情况统计表';

    public function exportData($parameters)
    {
        foreach (Tax::RATE_MAP as $row => $value) {
            $this->createData($this->data($parameters, $row));
        }
    }

    public function createData($value)
    {
        $this->setCell($this->cell)->setValue($value);
        $this->setCell($this->cell + 1);
    }

    /**
     * Author:LazyBench
     *
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setValue($data)
    {
        $this->workSheet->getCell("C{$this->cell}")->setValue($data['total']);
        $this->workSheet->getCell("D{$this->cell}")->setValue($data['personTaxIncomeYearTotal']);
        $this->workSheet->getCell("E{$this->cell}")->setValue($data['personTaxAmountTotal']);
        $this->workSheet->getCell("F{$this->cell}")->setValue($data['personTaxAmountLast']);
        $this->workSheet->getCell("G{$this->cell}")->setValue($data['personTax']);
    }

    /**
     * Author:LazyBench
     * @param $parameters
     * @param int $row
     * @return array
     */
    public function data($parameters, $row)
    {
        //                $debug = ' AND id in (281,420,426,418,421,424,415,419,278,425)';
        $debug = '';
        $month = $parameters['end'];
        $data = \UserStatisticsMonth::findFirst([
            'conditions' => 'month = :month: and rate = :rate: and is_filter=0'.$debug,
            'bind' => [
                'month' => $month,
                'rate' => $row,
            ],
            'columns' => "count(id_card) total,sum(person_tax_income_year_total) personTaxIncomeYearTotal,sum(person_tax_year_total) personTaxYearTotal,sum(person_tax) personTax",
        ]);
        $personTaxIncomeYearTotal = $data->personTaxIncomeYearTotal ?? 0;
        $personTaxYearTotal = $data->personTaxYearTotal ?? 0;
        $total = $data->total ?? 0;
        $personTax = $data->personTax ?? 0;
        $lastMonth = date('Ym', strtotime("{$this->endDate}05 00:00:00 -1month"));
        $lastData = \UserStatisticsMonth::findFirst([
            'conditions' => 'month = :month: and rate = :rate: and is_filter=0',
            'bind' => [
                'month' => $lastMonth,
                'rate' => $row,
            ],
            'columns' => 'SUM(person_tax_year_total) personTaxAmountLast',
        ]);
        $personTaxAmountLast = $lastData->personTaxAmountLast ?? 0;
        return [
            'total' => $total,
            'personTaxIncomeYearTotal' => $personTaxIncomeYearTotal,
            'personTaxAmountTotal' => $personTaxYearTotal,
            'personTaxAmountLast' => $personTaxAmountLast,
            'personTax' => $personTax,
        ];
    }

    /**
     * Author:LazyBench
     *
     * @param $date
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setMark($date)
    {
        $this->workSheet->getCell('C3')->setValue($date);
        $lastDate = strtotime("{$date} +1 month") - 100;
        $this->workSheet->getCell('E3')->setValue(date('Y-m-d', $lastDate));
        $this->workSheet->getCell('G3')->setValue(date('Y-m-d'));
    }
}
