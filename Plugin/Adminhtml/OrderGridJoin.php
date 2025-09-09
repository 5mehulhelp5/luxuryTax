<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Plugin\Adminhtml;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;

class OrderGridJoin
{
    /**
     * @param OrderGridCollection $subject
     * @param $printQuery
     * @param $logQuery
     * @return array|false[]
     */
    public function beforeLoad(
        OrderGridCollection $subject,
                            $printQuery = false,
                            $logQuery = false
    ) {
        if (!$subject->getFlag('andriy_luxurytax_joined')) {
            $subject->getSelect()->joinLeft(
                ['so' => $subject->getTable('sales_order')],
                'main_table.entity_id = so.entity_id',
                [
                    'luxury_tax_condition_amount' => 'luxury_tax_condition_amount',
                ]
            );
            $subject->setFlag('andriy_luxurytax_joined', true);
        }

        return [$printQuery, $logQuery];
    }
}
