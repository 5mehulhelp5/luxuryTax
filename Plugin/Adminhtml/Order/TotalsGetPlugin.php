<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Plugin\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\Totals as AdminTotals;
use Magento\Framework\DataObject;

class TotalsGetPlugin
{
    /**
     *
     * @param AdminTotals $subject
     * @param array $result
     * @return array
     */
    public function afterGetTotals(AdminTotals $subject, array $result): array
    {
        if (isset($result['luxury_tax'])) {
            return $result;
        }

        $order = $subject->getOrder();
        if (!$order) {
            return $result;
        }

        $amount = (float)$order->getData('luxury_tax');
        if ($amount <= 0) {
            return $result;
        }

        $row = new DataObject([
            'code'   => 'luxury_tax',
            'label'  => __('Luxury Tax'),
            'value'  => $amount,
            'strong' => false,
        ]);

        $out = [];
        $inserted = false;
        foreach ($result as $code => $obj) {
            if ($code === 'grand_total' && !$inserted) {
                $out['luxury_tax'] = $row;
                $inserted = true;
            }
            $out[$code] = $obj;
        }
        if (!$inserted) {
            $out['luxury_tax'] = $row;
        }

        return $out;
    }
}
