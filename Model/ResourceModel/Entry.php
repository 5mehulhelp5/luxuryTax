<?php

/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */

namespace Andriy\LuxuryTax\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Entry extends AbstractDb
{

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('luxury_tax', 'entity_id');
    }
}
