<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Model\Order\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class LuxuryTax extends AbstractTotal
{

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * @param Invoice $invoice
     * @return $this|LuxuryTax
     */
    public function collect(Invoice $invoice)
    {
        parent::collect($invoice);
        $enabled = $this->scopeConfig->isSetFlag(
            'andriy_luxurytax/general/enabled',
            ScopeInterface::SCOPE_STORE
        );

        if (!$enabled) {
            // нічого не робимо — податок вимкнено
            return $this;
        }
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
