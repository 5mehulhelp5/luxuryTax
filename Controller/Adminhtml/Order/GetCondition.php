<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Model\OrderFactory;

class GetCondition extends Action
{
    protected $orderFactory;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        OrderFactory $orderFactory,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->orderFactory = $orderFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $orderId = $this->getRequest()->getParam('order_id');
        $mockAmount = $orderId % 1000;

        return $result->setData([
            'success' => true,
            'condition_amount' => $mockAmount
        ]);
    }
}
