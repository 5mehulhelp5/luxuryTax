<?php
declare(strict_types=1);

namespace Andriy\LuxuryTax\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface LuxuryTaxInterface
 */
interface LuxuryTaxInterface extends CustomAttributesDataInterface
{
    const ENTITY_ID         = 'entity_id';
    const STATUS            = 'status';
    const NAME              = 'name';
    const DESCRIPTION       = 'description';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    const CONDITION_AMOUNT  = 'condition_amount';
    const TAX_RATE          = 'tax_rate';

    /**
     * @return int|null
     */
    public function getEntityId(): int;

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId(int $entityId);

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description);

    /**
     * @return int
     */
    public function getCustomerGroupId(): int;

    /**
     * @param int $customerGroupId
     * @return $this
     */
    public function setCustomerGroupId(int $customerGroupId);

    /**
     * @return string
     */
    public function getConditionAmount(): string;

    /**
     * @param string $amount
     * @return $this
     */
    public function setConditionAmount(string $amount);

    /**
     * @return string
     */
    public function getTaxRate(): string;

    /**
     * @param string $rate
     * @return $this
     */
    public function setTaxRate(string $rate);
}
