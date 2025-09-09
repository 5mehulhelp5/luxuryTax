<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */

namespace Andriy\LuxuryTax\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;


abstract class LuxuryTax extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Andriy_LuxuryTax::listing';
}
