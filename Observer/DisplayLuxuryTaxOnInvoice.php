<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class DisplayLuxuryTaxOnInvoice implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        $invoice = $block->getInvoice();

        $luxuryTaxAmount = $invoice->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            $block->addTotal(new \Magento\Framework\DataObject([
                'code' => 'luxury_tax',
                'value' => $luxuryTaxAmount,
                'label' => __('Luxury Tax'),
                'strong' => false,
                'area' => 'footer'
            ]), 'tax');
        }
    }
}
