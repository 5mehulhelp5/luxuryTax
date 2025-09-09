<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
declare(strict_types=1);

namespace Andriy\LuxuryTax\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Andriy\LuxuryTax\Model\ResourceModel\Entry\CollectionFactory;
use Magento\Framework\Api\Filter;

class Listing extends AbstractDataProvider
{
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

    public function addFilter(Filter $filter)
    {

        if ($filter->getField() === 'fulltext') {
            $v = '%' . $filter->getValue() . '%';
            $this->collection->addFieldToFilter(['name','description'], [['like'=>$v], ['like'=>$v]]);
            return;
        }
        parent::addFilter($filter);
    }
}
