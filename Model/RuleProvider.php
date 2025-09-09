<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Model;

use Andriy\LuxuryTax\Model\ResourceModel\Entry\CollectionFactory;

class RuleProvider
{
    public function __construct(private CollectionFactory $collectionFactory) {}

    /**
     * Повертає активне правило для групи з урахуванням порогу.
     * @return array|null ['condition_amount' => float, 'tax_rate' => float]
     */
    public function getApplicableRule(int $customerGroupId, float $baseSubtotalWithDiscount): ?array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 1)
            ->addFieldToFilter('customer_group_id', $customerGroupId)
            ->setPageSize(1);

        $rule = $collection->getFirstItem();
        if (!$rule->getId()) {
            return null;
        }

        $condition = (float)$rule->getData('condition_amount');
        if ($baseSubtotalWithDiscount <= $condition) {
            return null;
        }

        return [
            'condition_amount' => $condition,
            'tax_rate'         => (float)$rule->getData('tax_rate')
        ];
    }
}
