<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Controller\Adminhtml\Index;

use Andriy\LuxuryTax\Model\EntryRepository;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends LuxuryTax
{
    /**
     * @var
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var EntryRepository
     */
    protected EntryRepository $entryRepository;

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param EntryRepository $entryRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        EntryRepository $entryRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->entryRepository = $entryRepository;
        parent::__construct($context);
    }

    /**
     * @return Page|ResponseInterface|ResultInterface|\Magento\Framework\View\Result\Page|(\Magento\Framework\View\Result\Page&ResultInterface)
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $rowId = (int) $this->getRequest()->getParam('id');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        if ($rowId) {
            try {
                $entry = $this->entryRepository->get($rowId);
                $resultPage->getConfig()->getTitle()->prepend(__($entry['name']));
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This Luxury Tax no longer exists.'));

                return $this->_redirect('*/*/index');
            }
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Luxury Tax'));
        }

        return $resultPage;
    }
}
