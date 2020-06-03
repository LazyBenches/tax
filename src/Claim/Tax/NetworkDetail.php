<?php

namespace LazyBench\Tax\Claim\Tax;

use LazyBench\Tax\Claim\Base\ExportBase;
use LazyBench\Tax\Claim\Interfaces\ExportInterface;
use LazyBench\Tax\Constant\Tax;

/**
 * Author:LazyBench
 * Date:2019/1/9
 */
class NetworkDetail extends ExportBase implements ExportInterface
{
    private $end = '';
    public $rateAmount = [
        '5' => 0,
        '10' => 1500,
        '20' => 10500,
        '30' => 40500,
        '35' => 65500,
    ];
    protected $rateBase = [
        '5' => [-30000, 30000],
        '10' => [30000, 90000],
        '20' => [90000, 300000],
        '30' => [300000, 500000],
        '35' => [50000, 500000000],
    ];
    protected $copyFile = 'network.xls';
    protected $fileName = '网络平台企业个人所得税扣缴明细表';

    /**
     * Author:LazyBench
     *
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setValue($data)
    {
        $this->workSheet->getCell("A{$this->cell}")->setValue($this->cell - 5);
        isset($data['realName']) && $this->workSheet->getCell("B{$this->cell}")->setValue($data['realName']);
        $this->workSheet->getCell("C{$this->cell}")->setValue('身份证');
        isset($data['id_card']) && $this->workSheet->getCell("D{$this->cell}")->setValue($data['id_card']." ");
        $this->workSheet->getCell("E{$this->cell}")->setValue('中国');
        isset($data['mobile']) && $this->workSheet->getCell("F{$this->cell}")->setValue($data['mobile']);
        isset($data['province']) && $this->workSheet->getCell("G{$this->cell}")->setValue($data['province']);
        isset($data['amount']) && $this->workSheet->getCell("H{$this->cell}")->setValue($data['amount']);
        $this->workSheet->getCell("I{$this->cell}")->setValue('12%');
        $J = bcmul($data['amount'], Tax::RATE_DISCOUNT, 2);
        $this->workSheet->getCell("J{$this->cell}")->setValue($J);//个人月底累计J=H*I
        $this->workSheet->getCell("K{$this->cell}")->setValue("{$data['rate']}%");//个人所得税税率
        $this->workSheet->getCell("L{$this->cell}")->setValue($this->rateAmount[$data['rate']]);//速算扣除数
        $this->workSheet->getCell("M{$this->cell}")->setValue($data['personTaxAmountTotal']);//应纳税额=(J*K)-L
        $this->workSheet->getCell("N{$this->cell}")->setValue($data['personTaxAmountLast']);//个人已纳税（累计1-上个月自然月）
        $this->workSheet->getCell("O{$this->cell}")->setValue($data['person_tax']);//本期应补退税额 O=M-N
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
        $lastMonth = date('Ym', strtotime("{$this->endDate}05 00:00:00 -1month"));
        $return = [];
        foreach ($data as $value) {
            $idCard = new \IdCard();
            $card = $idCard->getIdCard($value['id_card']);
            $value['province'] = $card['province'] ?? '';
            $value['realName'] = $card['realname'] ?? '';
            $value['mobile'] = $card['mobile'] ?? '';
            $personTaxAmountLast = \UserStatisticsMonth::findFirst([
                'columns' => 'person_tax_year_total',
                'conditions' => 'id_card = :id_card: and month=:month:',
                'bind' => [
                    'id_card' => $value['id_card'],
                    'month' => $lastMonth,
                ],
            ]);
            $value['personTaxAmountLast'] = $personTaxAmountLast->person_tax_year_total ?? 0;
            if ($exportMode) {
                $this->setCell($this->cell)->setValue($value);
                $this->setCell($this->cell + 1);
            } else {
                $return[] = $value;
            }
        }
        if (!$exportMode) {
            return $return;
        }
    }

    /**
     * Author:LazyBench
     * @param $parameters
     * @param $page
     * @return array
     */
    public function getData($parameters, $page)
    {
        $this->end = $parameters['end'];
        $criteria = [
            'conditions' => 'month = :month: and is_filter= :is_filter:',
            'bind' => [
                'month' => $parameters['end'],
                'is_filter' => 0,
            ],
            'offset' => ($page - 1) * $this->limit,
            'limit' => $this->limit,
            'columns' => 'id,id_card,person_tax_income_year_total amount,person_tax_year_total personTaxAmountTotal,person_tax,rate',
        ];
        if (isset($parameters['id_card'])) {
            $criteria['conditions'] .= ' AND id_card=:id_card:';
            $criteria['bind']['id_card'] = $parameters['id_card'];
        }
        $data = \UserStatisticsMonth::find($criteria);
        return $data->toArray();
    }

    /**
     * Author:LazyBench
     *
     * @param $start
     * @param $end
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setMark($start, $end)
    {
        $this->workSheet->getCell('C3')->setValue($start);
        $this->workSheet->getCell('G3')->setValue($end);
    }
}
