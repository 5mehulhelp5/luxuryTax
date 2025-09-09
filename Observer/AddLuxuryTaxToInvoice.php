<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;

class AddLuxuryTaxToInvoice implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        $luxuryTaxAmount = $order->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            $invoice->setData('luxury_tax', $luxuryTaxAmount);
            $invoice->setData('base_luxury_tax', $luxuryTaxAmount);

            $invoice->setGrandTotal($invoice->getGrandTotal() + $luxuryTaxAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $luxuryTaxAmount);
        }
    }
}
