<?php

/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */

namespace Andriy\LuxuryTax\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Andriy_LuxuryTax::listing';

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Action\Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return Page|ResultInterface|ResponseInterface
     */
    public function execute(): \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Andriy_LuxuryTax::menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Luxury Tax'));
        return $resultPage;
    }


}
