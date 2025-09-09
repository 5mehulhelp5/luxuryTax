<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Service;

use Andriy\LuxuryTax\Model\ResourceModel\Entry\CollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;

class LuxuryTaxCalculator
{
    protected $collectionFactory;
    protected $customerSession;

    public function __construct(
        CollectionFactory $collectionFactory,
        CustomerSession $customerSession
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
    }

    public function calculateLuxuryTax($subtotal): float
    {

        $customerGroupId = $this->customerSession->getCustomerGroupId();

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_group_id', $customerGroupId);
        $collection->addFieldToFilter('condition_amount', ['lteq' => $subtotal]);
        $collection->addFieldToFilter('status', 1);
        $collection->setOrder('condition_amount', 'DESC');
        $rule = $collection->getFirstItem();

        if ($rule->getId()) {
            $taxRate = (float)$rule->getTaxRate();
            return ($subtotal * $taxRate) / 100;
        }

        return 0.0;
    }
}
