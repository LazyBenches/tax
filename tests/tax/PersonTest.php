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
     * 101000 及以下
     */
    public function testTax10300000()
    {
        $config = include(configPath.'config20201231.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('101000', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 100394, '101000 及以下 速算扣除 0.5%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 0.5%
     */
    public function testTax10300100()
    {
        $config = include(configPath.'config20201231.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonData('101000.01', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 99340.00984, '101000以上 速算扣除 0.5%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 1%
     */
    public function testTax20799676()
    {
        $config = include(configPath.'config20201231.php');
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


    /**
     * Author:LazyBench
     * 101000 及以下
     */
    public function testTaxBelow10300000()
    {
        $config = include(configPath.'config20201231.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $personLog = $tax->getPersonIncomeData('99220.00', '51062319860226901X', '202006', 0);
        $this->assertEquals($tax->ceil($personLog->personIncome, 4), 99818.92, '101000 及以下 速算扣除 0.5%');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 0.5%
     */
    public function testTaxAbove10300100()
    {
        $config = include(configPath.'config20201231.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $amount = '99220.00';
        $below = $tax->getPersonIncomeData($amount, '51062319860226901X', '202006', 0);
        $company = $tax->getCompanyData($amount);
        $personLog = $tax->getPersonIncomeAboveMatchData($company->taxAmount + $below->personWages, '99220.00', '51062319860226901X', '202006');
        $this->assertEquals($tax->ceil($personLog->personWagesLeft, 4), 101000.01, '103000以上 速算扣除 0.5%');
    }
}