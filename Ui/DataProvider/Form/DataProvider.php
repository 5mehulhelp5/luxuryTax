<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Ui\DataProvider\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Andriy\LuxuryTax\Model\EntryFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Data\CollectionFactory as DataCollectionFactory;

class DataProvider extends AbstractDataProvider
{
    private const PERSIST_KEY = 'andriy_luxurytax_form';

    private EntryFactory $modelFactory;
    private RequestInterface $request;
    private DataPersistorInterface $dataPersistor;

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        EntryFactory $modelFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        DataCollectionFactory $dataCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection     = $dataCollectionFactory->create();
        $this->modelFactory   = $modelFactory;
        $this->request        = $request;
        $this->dataPersistor  = $dataPersistor;
    }

    public function getData(): array
    {
        $result = [];

        $idParam = $this->request->getParam('entity_id') ?? $this->request->getParam('id');
        $id      = (int)($idParam ?? 0);

        if ($id > 0) {
            $model = $this->modelFactory->create()->load($id);
            if ($model->getId()) {
                $result[$id] = ['data' => $model->getData()];
            }
        }

        $persisted = $this->dataPersistor->get(self::PERSIST_KEY);
        if (!empty($persisted)) {
            $tmpId = (int)($persisted['entity_id'] ?? 0);
            $result[$tmpId] = ['data' => $persisted];
            $this->dataPersistor->clear(self::PERSIST_KEY);
        }

        return $result;
    }
}
