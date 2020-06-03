<?php

namespace LazyBench\Tax\Claim\Base;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Author:LazyBench
 * Date:2019/1/4
 */
class ExportBase
{

    protected $copyFile = '';
    protected $limit = 1000;
    protected $pIndex = 0;
    protected $fileName = '';
    protected $downloadPath = '';
    protected $fileType = 'xls';
    protected $driver;
    protected $cell = 4;
    protected $startCell = 4;
    protected $startDate = '';
    protected $endDate = '';
    /**
     * Author:LazyBench
     *
     * @var Worksheet
     */
    protected $workSheet;
    /**
     * Author:LazyBench
     *
     * @var Spreadsheet
     */
    protected $spreadSheet;
    protected $basisTax;
    protected $fillDate;
    protected $rate = [
        '0.07',
        '0.03',
        '0.02',
    ];


    protected $reduceCode = [
        '0.07' => '0007049901',
        '0.03' => '0061049901',
        '0.02' => '0099049901',
    ];

    protected $projectChildCode = [
        101090101,
        302030100,
        302160100,

    ];
    protected $projectCode = [
        10109,
        30203,
        30216,
    ];

    /**
     * 是否自动初始化载入xml配置，主要用在接口对接导出不需要excel驱动
     * ExportBase constructor.
     * @param bool $exportMode
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    final public function __construct(bool $exportMode = true)
    {
        if ($exportMode) {
            $this->loadSheetTemplate();
        }
    }

    /**
     * Author:LazyBench
     * 手动载入配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function loadSheetTemplate()
    {
        $path = configPath.'templates/';
        $this->spreadSheet = IOFactory::load($path.$this->copyFile);
        $this->setSheet(0);
    }

    /**
     * Author:LazyBench
     *
     * @param $pIndex
     * @return $this
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setSheet($pIndex)
    {
        $this->spreadSheet->setActiveSheetIndex($pIndex);
        $this->workSheet = $this->spreadSheet->getSheet($pIndex);
        return $this;
    }

    /**
     * Author:LazyBench
     *
     * @return Worksheet
     */
    public function getWorkSheet()
    {
        return $this->workSheet;
    }

    /**
     * Author:LazyBench
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    final public function export()
    {
        $fileName = "{$this->fileName}.{$this->fileType}";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$fileName");
        header('Cache-Control: max-age=0');//禁止缓存
        $writer = IOFactory::createWriter($this->spreadSheet, ucfirst($this->fileType));
        $writer->save('php://output');
        $this->spreadSheet->disconnectWorksheets();
        unset($this->spreadSheet);
        exit;
    }

    /**
     * Author:LazyBench
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save()
    {
        $writer = IOFactory::createWriter($this->spreadSheet, ucfirst($this->fileType));
        $fileName = "{$this->downloadPath}{$this->fileName}.{$this->fileType}";
        $writer->save($fileName);
        $this->spreadSheet->disconnectWorksheets();
        unset($this->spreadSheet);
    }

    /**
     * Author:LazyBench
     *
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
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
     * @param $date
     */
    public function setStartDate($date)
    {
        $this->startDate = $date;
    }

    /**
     * Author:LazyBench
     *
     * @param $date
     */
    public function setEndDate($date)
    {
        $this->endDate = $date;
    }

    /**
     * Author:LazyBench
     *
     * @param $cell
     */
    public function setStartCell($cell)
    {
        $this->setCell($cell);
        $this->startCell = $cell;
    }

    /**
     * Author:LazyBench
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }
}
