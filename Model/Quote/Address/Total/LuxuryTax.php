<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */

namespace Andriy\LuxuryTax\Model\Quote\Address\Total;

use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Andriy\LuxuryTax\Model\RuleProvider;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


class LuxuryTax extends AbstractTotal
{
    public const TOTAL_CODE = 'luxury_tax';

    public function __construct(private RuleProvider $ruleProvider,
                                ScopeConfigInterface $scopeConfig
    )
    {
        $this->setCode(self::TOTAL_CODE);
        $this->scopeConfig = $scopeConfig;
    }


    // Model/Quote/Address/Total/LuxuryTax.php

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this|LuxuryTax
     */
    public function collect(
        Quote                                    $quote,
        ShippingAssignmentInterface              $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $enabled = $this->scopeConfig->isSetFlag(
            'andriy_luxurytax/general/enabled',
            ScopeInterface::SCOPE_STORE
        );

        if (!$enabled) {
            // нічого не робимо — податок вимкнено
            return $this;
        }
        $items = $shippingAssignment->getItems();
        if (!\is_array($items) || !\count($items)) {
            return $this;
        }

        $address = $shippingAssignment->getShipping()
            ? $shippingAssignment->getShipping()->getAddress()
            : $quote->getBillingAddress();

        $this->clearAmounts($total, $address);

        $baseSubtotalWithDiscount = (float)$address->getBaseSubtotalWithDiscount();
        if ($baseSubtotalWithDiscount <= 0.0001) {
            return $this;
        }

        $customerGroupId = (int)$quote->getCustomerGroupId() ?: (int)$quote->getCustomer()->getGroupId();
        if ($customerGroupId <= 0) {
            $customerGroupId = (int)\Magento\Customer\Model\Group::NOT_LOGGED_IN_ID;
        }

        $rule = $this->ruleProvider->getApplicableRule($customerGroupId, $baseSubtotalWithDiscount);
        if (!$rule) {
            return $this;
        }

        $ratePercent = (float)$rule['tax_rate'];
        if ($ratePercent <= 0) {
            return $this;
        }

        $baseTax = round($baseSubtotalWithDiscount * ($ratePercent / 100), 2);

        $rate = (float)$quote->getBaseToQuoteRate() ?: 1.0;
        $tax = round($baseTax * $rate, 2);

        $address->setData('luxury_tax', $tax);
        $address->setData('base_luxury_tax', $baseTax);

        $address->setData('luxury_tax_condition_amount', $rule['condition_amount']);
        $address->setData('base_luxury_tax_condition_amount', $rule['condition_amount']);

        $total->setTotalAmount('luxury_tax', $tax);
        $total->setBaseTotalAmount('base_luxury_tax', $baseTax);
        $total->setData('luxury_tax', $tax);
        $total->setData('base_luxury_tax', $baseTax);


        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array|null
     */
    public function fetch(
        \Magento\Quote\Model\Quote               $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        $luxuryTaxAmount = $total->getLuxuryTaxAmount();

        if ($luxuryTaxAmount > 0) {
            return [
                'code' => 'luxury_tax',
                'title' => __('Luxury Tax'),
                'value' => $luxuryTaxAmount
            ];
        }

        return null;
    }

    /**
     * @param $total
     * @param $address
     * @return void
     */
    private function clearAmounts($total, $address): void
    {
        $address->setData(self::TOTAL_CODE, 0.0);
        $address->setData('base_' . self::TOTAL_CODE, 0.0);
        $total->setTotalAmount(self::TOTAL_CODE, 0.0);
        $total->setBaseTotalAmount(self::TOTAL_CODE, 0.0);
    }
}
