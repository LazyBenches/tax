<?php declare(strict_types=1);


namespace LazyBench\Tax\Model;

/**
 * 用户月统计
 * Class UserMonth
 *
 * @since 2.0
 *
 * @Entity(table="user_month", pool="settlement.pool")
 */
class UserMonth
{
    /**
     * 
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * 身份证信息
     *
     * @Column()
     *
     * @var string
     */
    private $idCard;

    /**
     * 税前工资
     *
     * @Column()
     *
     * @var float
     */
    private $taxWages;

    /**
     * 计税依据当前
     *
     * @Column()
     *
     * @var float
     */
    private $taxBasis;

    /**
     * 税基
     *
     * @Column()
     *
     * @var float|null
     */
    private $taxBase;

    /**
     * 累计到当前月税基
     *
     * @Column()
     *
     * @var float|null
     */
    private $taxBaseYearTotal;

    /**
     * 应税收入
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxIncome;

    /**
     * 应税收入累计
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxIncomeYearTotal;

    /**
     * 实际到账（税后工资）
     *
     * @Column()
     *
     * @var float|null
     */
    private $personIncome;

    /**
     * 个人综合税费
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxAmount;

    /**
     * 应纳税额累计
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxAmountTotal;

    /**
     * 个人应算综合税费
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxAmountShould;

    /**
     * 个人增值税
     *
     * @Column()
     *
     * @var float|null
     */
    private $personAddTax;

    /**
     * 增值税算价
     *
     * @Column()
     *
     * @var float|null
     */
    private $personAddTaxing;

    /**
     * 个人附加税
     *
     * @Column()
     *
     * @var float|null
     */
    private $personAddTaxExt;

    /**
     * 附加算价
     *
     * @Column()
     *
     * @var float|null
     */
    private $personAddTaxExtIng;

    /**
     * 个人印花税
     *
     * @Column()
     *
     * @var float|null
     */
    private $personStampTax;

    /**
     * 个人所得税
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTax;

    /**
     * 税基
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxYearTotal;

    /**
     * 已纳税额累计
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxAmountLast;

    /**
     * 个人所得税
     *
     * @Column()
     *
     * @var float|null
     */
    private $personTaxing;

    /**
     * 年月数字
     *
     * @Column()
     *
     * @var int|null
     */
    private $month;

    /**
     * 是否缴纳增值
     *
     * @Column()
     *
     * @var int|null
     */
    private $isAdd;

    /**
     * 税率
     *
     * @Column()
     *
     * @var int|null
     */
    private $rate;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $createdAt;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $updatedAt;


    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $idCard
     *
     * @return void
     */
    public function setIdCard(string $idCard): void
    {
        $this->idCard = $idCard;
    }

    /**
     * @param float $taxWages
     *
     * @return void
     */
    public function setTaxWages(float $taxWages): void
    {
        $this->taxWages = $taxWages;
    }

    /**
     * @param float $taxBasis
     *
     * @return void
     */
    public function setTaxBasis(float $taxBasis): void
    {
        $this->taxBasis = $taxBasis;
    }

    /**
     * @param float|null $taxBase
     *
     * @return void
     */
    public function setTaxBase(?float $taxBase): void
    {
        $this->taxBase = $taxBase;
    }

    /**
     * @param float|null $taxBaseYearTotal
     *
     * @return void
     */
    public function setTaxBaseYearTotal(?float $taxBaseYearTotal): void
    {
        $this->taxBaseYearTotal = $taxBaseYearTotal;
    }

    /**
     * @param float|null $personTaxIncome
     *
     * @return void
     */
    public function setPersonTaxIncome(?float $personTaxIncome): void
    {
        $this->personTaxIncome = $personTaxIncome;
    }

    /**
     * @param float|null $personTaxIncomeYearTotal
     *
     * @return void
     */
    public function setPersonTaxIncomeYearTotal(?float $personTaxIncomeYearTotal): void
    {
        $this->personTaxIncomeYearTotal = $personTaxIncomeYearTotal;
    }

    /**
     * @param float|null $personIncome
     *
     * @return void
     */
    public function setPersonIncome(?float $personIncome): void
    {
        $this->personIncome = $personIncome;
    }

    /**
     * @param float|null $personTaxAmount
     *
     * @return void
     */
    public function setPersonTaxAmount(?float $personTaxAmount): void
    {
        $this->personTaxAmount = $personTaxAmount;
    }

    /**
     * @param float|null $personTaxAmountTotal
     *
     * @return void
     */
    public function setPersonTaxAmountTotal(?float $personTaxAmountTotal): void
    {
        $this->personTaxAmountTotal = $personTaxAmountTotal;
    }

    /**
     * @param float|null $personTaxAmountShould
     *
     * @return void
     */
    public function setPersonTaxAmountShould(?float $personTaxAmountShould): void
    {
        $this->personTaxAmountShould = $personTaxAmountShould;
    }

    /**
     * @param float|null $personAddTax
     *
     * @return void
     */
    public function setPersonAddTax(?float $personAddTax): void
    {
        $this->personAddTax = $personAddTax;
    }

    /**
     * @param float|null $personAddTaxing
     *
     * @return void
     */
    public function setPersonAddTaxing(?float $personAddTaxing): void
    {
        $this->personAddTaxing = $personAddTaxing;
    }

    /**
     * @param float|null $personAddTaxExt
     *
     * @return void
     */
    public function setPersonAddTaxExt(?float $personAddTaxExt): void
    {
        $this->personAddTaxExt = $personAddTaxExt;
    }

    /**
     * @param float|null $personAddTaxExtIng
     *
     * @return void
     */
    public function setPersonAddTaxExtIng(?float $personAddTaxExtIng): void
    {
        $this->personAddTaxExtIng = $personAddTaxExtIng;
    }

    /**
     * @param float|null $personStampTax
     *
     * @return void
     */
    public function setPersonStampTax(?float $personStampTax): void
    {
        $this->personStampTax = $personStampTax;
    }

    /**
     * @param float|null $personTax
     *
     * @return void
     */
    public function setPersonTax(?float $personTax): void
    {
        $this->personTax = $personTax;
    }

    /**
     * @param float|null $personTaxYearTotal
     *
     * @return void
     */
    public function setPersonTaxYearTotal(?float $personTaxYearTotal): void
    {
        $this->personTaxYearTotal = $personTaxYearTotal;
    }

    /**
     * @param float|null $personTaxAmountLast
     *
     * @return void
     */
    public function setPersonTaxAmountLast(?float $personTaxAmountLast): void
    {
        $this->personTaxAmountLast = $personTaxAmountLast;
    }

    /**
     * @param float|null $personTaxing
     *
     * @return void
     */
    public function setPersonTaxing(?float $personTaxing): void
    {
        $this->personTaxing = $personTaxing;
    }

    /**
     * @param int|null $month
     *
     * @return void
     */
    public function setMonth(?int $month): void
    {
        $this->month = $month;
    }

    /**
     * @param int|null $isAdd
     *
     * @return void
     */
    public function setIsAdd(?int $isAdd): void
    {
        $this->isAdd = $isAdd;
    }

    /**
     * @param int|null $rate
     *
     * @return void
     */
    public function setRate(?int $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdCard(): ?string
    {
        return $this->idCard;
    }

    /**
     * @return float
     */
    public function getTaxWages(): ?float
    {
        return $this->taxWages;
    }

    /**
     * @return float
     */
    public function getTaxBasis(): ?float
    {
        return $this->taxBasis;
    }

    /**
     * @return float|null
     */
    public function getTaxBase(): ?float
    {
        return $this->taxBase;
    }

    /**
     * @return float|null
     */
    public function getTaxBaseYearTotal(): ?float
    {
        return $this->taxBaseYearTotal;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxIncome(): ?float
    {
        return $this->personTaxIncome;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxIncomeYearTotal(): ?float
    {
        return $this->personTaxIncomeYearTotal;
    }

    /**
     * @return float|null
     */
    public function getPersonIncome(): ?float
    {
        return $this->personIncome;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxAmount(): ?float
    {
        return $this->personTaxAmount;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxAmountTotal(): ?float
    {
        return $this->personTaxAmountTotal;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxAmountShould(): ?float
    {
        return $this->personTaxAmountShould;
    }

    /**
     * @return float|null
     */
    public function getPersonAddTax(): ?float
    {
        return $this->personAddTax;
    }

    /**
     * @return float|null
     */
    public function getPersonAddTaxing(): ?float
    {
        return $this->personAddTaxing;
    }

    /**
     * @return float|null
     */
    public function getPersonAddTaxExt(): ?float
    {
        return $this->personAddTaxExt;
    }

    /**
     * @return float|null
     */
    public function getPersonAddTaxExtIng(): ?float
    {
        return $this->personAddTaxExtIng;
    }

    /**
     * @return float|null
     */
    public function getPersonStampTax(): ?float
    {
        return $this->personStampTax;
    }

    /**
     * @return float|null
     */
    public function getPersonTax(): ?float
    {
        return $this->personTax;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxYearTotal(): ?float
    {
        return $this->personTaxYearTotal;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxAmountLast(): ?float
    {
        return $this->personTaxAmountLast;
    }

    /**
     * @return float|null
     */
    public function getPersonTaxing(): ?float
    {
        return $this->personTaxing;
    }

    /**
     * @return int|null
     */
    public function getMonth(): ?int
    {
        return $this->month;
    }

    /**
     * @return int|null
     */
    public function getIsAdd(): ?int
    {
        return $this->isAdd;
    }

    /**
     * @return int|null
     */
    public function getRate(): ?int
    {
        return $this->rate;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

}
