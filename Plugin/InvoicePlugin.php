<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Plugin;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\Data\InvoiceInterface;

class InvoicePlugin
{
    /**
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface[]
     */
    public function beforeSave(InvoiceRepositoryInterface $subject, InvoiceInterface $invoice)
    {
        $order = $invoice->getOrder();
        $luxuryTaxAmount = $order->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            $invoice->setData('luxury_tax', $luxuryTaxAmount);
            $invoice->setData('base_luxury_tax', $luxuryTaxAmount);

            $invoice->setGrandTotal($invoice->getGrandTotal() + $luxuryTaxAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $luxuryTaxAmount);
        }

        return [$invoice];
    }
}
