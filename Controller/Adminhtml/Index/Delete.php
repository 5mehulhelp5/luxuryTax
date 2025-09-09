<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Andriy\LuxuryTax\Model\EntryFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Andriy_LuxuryTax::delete';

    public function __construct(
        Action\Context $context,
        private readonly EntryFactory $entryFactory,
        private readonly RedirectFactory $redirectFactory
    ) { parent::__construct($context); }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $resultRedirect = $this->redirectFactory->create();

        if ($id) {
            $model = $this->entryFactory->create()->load($id);
            if ($model->getId()) {
                try {
                    $model->delete();
                    $this->messageManager->addSuccessMessage(__('The item has been deleted.'));
                } catch (\Throwable $e) {
                    $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
                    return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
                }
            }
        }
        return $resultRedirect->setPath('luxurytax/index/index');
    }
}
