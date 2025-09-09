<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Controller\Adminhtml\Index;

use Andriy\LuxuryTax\Api\LuxuryTaxRepositoryInterface;
use Andriy\LuxuryTax\Model\EntryFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends LuxuryTax
{
    /**
     * @return PointsFactory
     */
    protected EntryFactory $entryFactory;

    /**
     * @return DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var LuxuryTaxRepositoryInterface
     */
    protected LuxuryTaxRepositoryInterface $luxuryTaxRepositoryInterface;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param PointsFactory $pointsFactory
     * @param LuxuryTaxRepositoryInterface $luxuryTaxRepositoryInterface
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        EntryFactory $entryFactory,
        LuxuryTaxRepositoryInterface $luxuryTaxRepositoryInterface
    ) {
        /** @noinspection PhpUndefinedFieldInspection */
        $this->luxuryTaxRepositoryInterface = $luxuryTaxRepositoryInterface;
        $this->dataPersistor = $dataPersistor;
        $this->entryFactory = $entryFactory;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {

            /** @noinspection PhpUndefinedMethodInspection */
            $model = $this->entryFactory->create();
            $model->setData($data);
            try {
                $this->messageManager->addSuccessMessage(__('You saved the Point'));
                $this->dataPersistor->clear('andriy_points_item');

                $this->luxuryTaxRepositoryInterface->save($model);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Point.'));
            }

            $this->dataPersistor->set('andriy_points_item', $data);

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
