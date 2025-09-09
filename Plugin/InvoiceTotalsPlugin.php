<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Plugin;

use Magento\Sales\Block\Adminhtml\Order\Invoice\Totals;

class InvoiceTotalsPlugin
{
    /**
     * @param Totals $block
     * @param $result
     * @return mixed
     */
    public function afterGetTotals(Totals $block, $result)
    {
        $invoice = $block->getInvoice();
        $luxuryTaxAmount = $invoice->getData('luxury_tax');

        if ($luxuryTaxAmount > 0) {
            $result['luxury_tax'] = new \Magento\Framework\DataObject([
                'code' => 'luxury_tax',
                'field' => 'luxury_tax',
                'value' => $luxuryTaxAmount,
                'label' => __('Luxury Tax'),
                'strong' => false,
                'area' => 'footer'
            ]);
        }
        return $result;
    }
}
