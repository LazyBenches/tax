<?php declare(strict_types=1);


namespace LazyBench\Tax\Model;


/**
 * 服务费阶梯表
 * Class TaxRate
 *
 * @since 2.0
 *
 * @Entity(table="tax_rate", pool="settlement.pool")
 */
class TaxRate
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
     * 起点范围
     *
     * @Column()
     *
     * @var float
     */
    private $from;

    /**
     * 终点范围
     *
     * @Column()
     *
     * @var float
     */
    private $to;

    /**
     * 比率
     *
     * @Column()
     *
     * @var float
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
     * @param float $from
     *
     * @return void
     */
    public function setFrom(float $from): void
    {
        $this->from = $from;
    }

    /**
     * @param float $to
     *
     * @return void
     */
    public function setTo(float $to): void
    {
        $this->to = $to;
    }

    /**
     * @param float $rate
     *
     * @return void
     */
    public function setRate(float $rate): void
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
     * @return float
     */
    public function getFrom(): ?float
    {
        return $this->from;
    }

    /**
     * @return float
     */
    public function getTo(): ?float
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function getRate(): ?float
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
