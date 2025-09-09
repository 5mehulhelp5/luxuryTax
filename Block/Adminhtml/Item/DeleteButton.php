<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Block\Adminhtml\Item;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $id = $this->context->getRequest()->getParam('id');

        if (!$id) {
            return [];
        }

        return [
            'label' => __('Delete Luxury Tax'),
            'class' => 'delete primary',
            'on_click' => sprintf(
                "deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this luxury tax?'),
                $this->getDeleteUrl()
            ),
            'sort_order' => 92,
        ];
    }

    /**
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', [
            'id' => $this->context->getRequest()->getParam('id')
        ]);
    }
}
