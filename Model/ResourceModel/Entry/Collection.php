<?php

/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Model\ResourceModel\Entry;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Andriy\LuxuryTax\Model\Entry as EntryModel;
use Andriy\LuxuryTax\Model\ResourceModel\Entry as EntryResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(EntryModel::class, EntryResource::class);
    }
}
