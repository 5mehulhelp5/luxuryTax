<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Andriy\LuxuryTax\Model\ResourceModel\Entry\CollectionFactory;

class Form extends AbstractDataProvider
{
    private array $loadedData = [];

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        foreach ($this->collection->getItems() as $item) {
            $this->loadedData[(int)$item->getId()] = $item->getData();
        }
        return $this->loadedData;
    }
}
