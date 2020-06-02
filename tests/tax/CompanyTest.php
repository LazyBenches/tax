<?php
/**
 * Created by PhpStorm.
 * Email:jwy226@qq.com
 * User: LazyBench
 * Date: 2020/6/1
 * Time: 17:48
 */

class CompanyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Author:LazyBench
     * 103000 及以下
     */
    public function testTax10300000()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $companyLog = $tax->getCompanyData('103000');
        $this->assertEquals($tax->ceil($companyLog->poundageBase, 4), 109954.354, '103000 及以下 速算扣除 0.5% 企业实际支付');
        $this->assertEquals($tax->ceil($companyLog->addTax, 4), 6180, '103000 及以下 速算扣除 0.5% 增值税 ');
        $this->assertEquals($tax->ceil($companyLog->addTaxExt, 4), 741.6, '103000 及以下 速算扣除 0.5% 企业应缴附加税');
        $this->assertEquals($tax->ceil($companyLog->stampTax, 4), 32.754, '103000 及以下 速算扣除 0.5% 企业应缴印花税');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 0.5%
     */
    public function testTax10300100()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $companyLog = $tax->getCompanyData('103001');
        $this->assertEquals($tax->ceil($companyLog->poundageBase, 4), 109955.4215, '103000 及以上 速算扣除 0.5% 企业实际支付');
        $this->assertEquals($tax->ceil($companyLog->addTax, 4), 6180.06, '103000 及以上 速算扣除 0.5% 增值税 ');
        $this->assertEquals($tax->ceil($companyLog->addTaxExt, 4), 741.6072, '103000 及以上 速算扣除 0.5% 企业应缴附加税');
        $this->assertEquals($tax->ceil($companyLog->stampTax, 4), 32.754318, '103000 及以上 速算扣除 0.5% 企业应缴印花税');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 1%
     */
    public function testTax20799676()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $companyLog = $tax->getCompanyData('207996.76');
        $this->assertEquals($tax->ceil($companyLog->poundageBase, 4), 222040.2852, '103000 及以上 速算扣除1% 企业实际支付');
        $this->assertEquals($tax->ceil($companyLog->addTax, 4), 12479.8056, '103000 及以上 速算扣除 1% 增值税 ');
        $this->assertEquals($tax->ceil($companyLog->addTaxExt, 4), 1497.576672, '103000 及以上 速算扣除 1% 企业应缴附加税');
        $this->assertEquals($tax->ceil($companyLog->stampTax, 4), 66.14296968, '103000 及以上 速算扣除 1% 企业应缴印花税');
    }

    /**
     * Author:LazyBench
     * 103000以上 速算扣除 1%
     */
    public function testTax25750100()
    {
        $config = include(configPath.'config.php');
        $tax = new \LazyBench\Tax\Tax($config);
        $companyLog = $tax->getCompanyData('257501');
        $this->assertEquals($tax->ceil($companyLog->poundageBase, 4), 274886.9525, '103000 及以上 速算扣除 5% 企业实际支付');
        $this->assertEquals($tax->ceil($companyLog->addTax, 4), 15450.06, '103000 及以上 速算扣除 5% 增值税 ');
        $this->assertEquals($tax->ceil($companyLog->addTaxExt, 4), 1854.0072, '103000 及以上 速算扣除 5% 企业应缴附加税');
        $this->assertEquals($tax->ceil($companyLog->stampTax, 4), 81.885318, '103000 及以上 速算扣除 5% 企业应缴印花税');
    }

}