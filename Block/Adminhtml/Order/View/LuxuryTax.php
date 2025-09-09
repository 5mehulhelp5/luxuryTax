<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Block\Adminhtml\Order\View;

use Magento\Sales\Block\Adminhtml\Order\Totals;

class LuxuryTax extends Totals
{
    /**
     * @return $this|LuxuryTax
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        $order = $this->getOrder();
        $amount = (float)$order->getData('luxury_tax');

        if ($amount > 0) {
            $this->_totals['luxury_tax'] = new \Magento\Framework\DataObject([
                'code'  => 'luxury_tax',
                'value' => $amount,
                'label' => __('Luxury Tax'),
            ]);
        }

        return $this;
    }
}
