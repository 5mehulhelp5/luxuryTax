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
use Andriy\LuxuryTax\Model\ResourceModel\Entry\CollectionFactory;
use Magento\Framework\Controller\ResultInterface;
use Andriy\LuxuryTax\Model\EntryFactory;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Andriy_LuxuryTax::delete';

    public function __construct(
        Action\Context $context,
        private readonly EntryFactory $entryFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly RedirectFactory $redirectFactory
    ) { parent::__construct($context); }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $ids = (array)$this->getRequest()->getParam('selected', []);
        $resultRedirect = $this->redirectFactory->create();

        if (!$ids) {
            $this->messageManager->addErrorMessage(__('Please select item(s).'));
            return $resultRedirect->setPath('luxurytax/index/index');
        }

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $ids]);

        $deleted = 0;
        foreach ($collection as $item) {
            $model = $this->entryFactory->create()->load($item->getId());

            try {

//                die(var_dump($item->deleteById($item->getId())));
                $model->delete();
                $deleted++;
            } catch (\Throwable $e) {
                // continue
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $deleted));
        return $resultRedirect->setPath('luxurytax/index/index');
    }
}
