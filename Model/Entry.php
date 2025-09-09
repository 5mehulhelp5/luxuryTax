<?php

/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */

namespace Andriy\LuxuryTax\Model;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Andriy\LuxuryTax\Api\Data\LuxuryTaxInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Entry extends AbstractModel implements IdentityInterface, LuxuryTaxInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'luxury_tax';
    const ENTITY_ID = 'entity_id';
    const STATUS = 'status';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CUSTOMER_GROUP_ID = 'customer_group_id';
    const CONDITION_AMOUNT = 'condition_amount';
    const TAX_RATE = 'tax_rate';
    /**
     * @var string
     */
    protected $_cacheTag = 'luxury_tax';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'luxury_tax';


    /**
     * @throws LocalizedException
     */
    protected function _construct()
    {
        $this->_init(\Andriy\LuxuryTax\Model\ResourceModel\Entry::class);
    }

    /** @noinspection PhpMissingReturnTypeInspection */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getCustomAttribute($attributeCode)
    {
        // TODO: Implement getCustomAttribute() method.
    }

    public function setCustomAttribute($attributeCode, $attributeValue)
    {
        // TODO: Implement setCustomAttribute() method.
    }

    public function getCustomAttributes()
    {
        // TODO: Implement getCustomAttributes() method.
    }

    public function setCustomAttributes(array $attributes)
    {
        // TODO: Implement setCustomAttributes() method.
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    public function getStatus(): int
    {

        return $this->getData(self::STATUS);
    }

    public function setStatus(int $status)
    {

        return $this->setData(self::STATUS, $status);
    }

    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription(?string $description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getCustomerGroupId(): int
    {
        return $this->getData(self::CUSTOMER_GROUP_ID);
    }

    public function setCustomerGroupId(int $customerGroupId)
    {
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }

    public function getConditionAmount(): string
    {
        return $this->getData(self::CONDITION_AMOUNT);
    }

    public function setConditionAmount(string $amount)
    {
        return $this->setData(self::CONDITION_AMOUNT, $amount);
    }

    public function getTaxRate(): string
    {
        return $this->getData(self::TAX_RATE);
    }

    public function setTaxRate(string $rate)
    {
        return $this->setData(self::TAX_RATE, $rate);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }
}
