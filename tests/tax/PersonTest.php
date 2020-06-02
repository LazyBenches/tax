<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/6/1
 * Time: 17:48
 */

class PersonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Author:LazyBench
     * 103000 及以下
     */
    public function testTax10300000()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('103000', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 102382, '103000 及以下 速算扣除 0.5%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 0.5%
     */
    public function testTax10300100()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('103001', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 99040.96155, '103000以上 速算扣除 0.5%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 1%
     */
    public function testTax20799676()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('207996.76', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 199999.9914, '103000以上 速算扣除 1%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 1%
     */
    public function testTax25750100()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('257501', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 247600.9557, '103000以上 速算扣除 1%');
    }

}