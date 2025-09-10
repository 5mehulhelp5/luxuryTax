<?php
declare(strict_types=1);

namespace Andriy\LuxuryTax\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class GridInit extends Template
{
    public function __construct(
        Template\Context $context,
        private ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getColors(): array
    {
        return [
            'lt100'   => (string)$this->scopeConfig->getValue('tax/andriy_luxurytax_highlight/color_lt_100', ScopeInterface::SCOPE_STORE) ?: '#ffffff',
            'btw'     => (string)$this->scopeConfig->getValue('tax/andriy_luxurytax_highlight/color_100_1000', ScopeInterface::SCOPE_STORE) ?: '#fffbbb',
            'gt1000'  => (string)$this->scopeConfig->getValue('tax/andriy_luxurytax_highlight/color_gt_1000', ScopeInterface::SCOPE_STORE) ?: '#bbffbb',
            'enabled' => $this->scopeConfig->isSetFlag('tax/andriy_luxurytax_highlight/enabled', ScopeInterface::SCOPE_STORE),
        ];
    }
}
