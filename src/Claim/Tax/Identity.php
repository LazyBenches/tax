<?php

namespace LazyBench\Tax\Claim\Tax;

use LazyBench\Tax\Claim\Interfaces\ExportInterface;
use LazyBench\Tax\Claim\Base\ExportBase;

/**
 * Author:LazyBench
 * Date:2019/1/28
 */
class Identity extends ExportBase implements ExportInterface
{
    protected $downloadFiles;
    protected $downloadPath;
    protected $copyFile = 'identity.xls';
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
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setValue($data)
    {
        $data['personalTaxBasis'] <= $this->basisTax && $data['taxAmount'] = 0;
        foreach ($this->cellKeys as $key => $valueKey) {
            if (isset($data[$valueKey])) {
                $this->workSheet->getCell("{$key}{$this->cell}")->setValue($data[$valueKey]." ");
            }
        }
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setDownPath(array $path)
    {
        $dir = '';
        foreach ($path as $value) {
            $dir = $dir ? $dir.DIRECTORY_SEPARATOR.$value : $value;
            if (!is_dir($dir) && !mkdir($dir, 0777)) {
                return false;
            }
        }
        $this->downloadPath = $dir.DIRECTORY_SEPARATOR;
        return true;
    }

    public function setDate($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, -2, 2);
        $start = date('Y.m.d', strtotime("{$year}-{$month}-01"));
        $end = date('Y.m.d', strtotime("{$year}-{$month}-01 +1month") - 100);
        $value = "所属期：{$start}--{$end}";
        $this->workSheet->getCell('E3')->setValue($value);
    }

    public function getDownPath()
    {
        return $this->downloadPath;
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
     * @param $name
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setTitle($name)
    {
        $value = "完税证明-{$name}";
        $this->workSheet->getCell('A2')->setValue($value);
        $this->workSheet->setTitle($name);
    }


    /**
     * Author:LazyBench
     * @param array $idCards
     * @param $date
     * @return array
     */
    public function getSaveData(array $idCards, $date)
    {
        $data = \UserStatisticsMonth::find([
            'conditions' => 'month = :month: and id_card in ({id_card:array})',
            'bind' => [
                'month' => $date,
                'id_card' => $idCards,
            ],
            'columns' => 'id_card idCard,tax_basis personalTaxBasis,tax_wages personalWages,tax_basis personalTaxBasis,
             person_add_tax addTax,person_add_tax_ext addTaxExt,person_tax_amount taxAmount',
            'order' => 'id desc',
        ]);
        return $data->toArray();
    }

    public function createZip($zipName, $downloadFiles)
    {
        $zip = new \ZipArchive();
        $zipName = $this->downloadPath.$zipName;
        @unlink($zipName);
        if (!$zip->open($zipName, \ZipArchive::CREATE)) {
            return false;
        }
        if ($downloadFiles) {
            foreach ($downloadFiles as $key => $path) {
                $path = $path.'.'.$this->fileType;
                $fileName = $key.'.'.$this->fileType;
                $zip->addFile($path, $fileName);
            }
        }
        $zip->close();
        if (!file_exists($zipName)) {
            return false;
        }
        return $zipName;
    }

    public function download($zipName)
    {
        $zipName = $this->downloadPath.$zipName;
        if (!file_exists($zipName)) {
            exit('文件压缩失败！或者未生成压缩包');
        }
        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Length: '.filesize($zipName));
        header('Content-Disposition: attachment; filename="'.basename($zipName).'"');
        @readfile($zipName);
        exit;
    }
}
