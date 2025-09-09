<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Plugin;

use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;

class SaveToOrderPlugin
{
    /**
     * @param QuoteManagement $subject
     * @param OrderInterface $order
     * @param Quote $quote
     * @return OrderInterface
     */
    public function afterSubmit(QuoteManagement $subject, OrderInterface $order, Quote $quote)
    {
        $address = $quote->getShippingAddress() ?: $quote->getBillingAddress();
        $luxuryTaxAmount = $address->getData('luxury_tax');
        $conditionAmount = $address->getData('luxury_tax_condition_amount');

        if ($luxuryTaxAmount > 0) {
            $order->setData('tax_amount', $luxuryTaxAmount);
            $order->setData('base_tax_amount', $address->getData('base_luxury_tax'));

            $order->setData('luxury_tax_condition_amount', $conditionAmount);
            $order->setData('base_luxury_tax_condition_amount', $address->getData('base_luxury_tax_condition_amount'));

            $order->save();
        }

        return $order;
    }
}
