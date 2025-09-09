<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Model\Order\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class LuxuryTax extends AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this|LuxuryTax
     */
    public function collect(Invoice $invoice)
    {
        parent::collect($invoice);

        $luxuryTaxAmount = $invoice->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            $this->addTotalAmount('luxury_tax', $luxuryTaxAmount);
            $this->addBaseTotalAmount('luxury_tax', $luxuryTaxAmount);

            $invoice->setGrandTotal($invoice->getGrandTotal() + $luxuryTaxAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $luxuryTaxAmount);
        }

        return $this;
    }

    /**
     * @param Invoice $invoice
     * @return array|null
     */
    public function fetch(Invoice $invoice)
    {
        $luxuryTaxAmount = $invoice->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            return [
                'code' => 'luxury_tax',
                'title' => __('Luxury Tax'),
                'value' => $luxuryTaxAmount
            ];
        }

        return null;
    }
}
