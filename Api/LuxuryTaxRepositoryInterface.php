<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Api;

use Andriy\LuxuryTax\Api\Data\LuxuryTaxInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

interface LuxuryTaxRepositoryInterface
{

    /**
     * @param LuxuryTaxInterface $entry
     * @return LuxuryTaxInterface
     * @throws CouldNotSaveException
     * @since 100.1.0
     */
    public function save(LuxuryTaxInterface $entry): LuxuryTaxInterface;

    /**
     * @param int $entryId
     * @return LuxuryTaxInterface
     * @throws LuxuryTaxInterface
     * @since 100.1.0
     */
    public function get(int $entryId): array;

    /**
     * @param LuxuryTaxInterface $entry
     * @return bool
     * @throws LuxuryTaxInterface
     * @since 100.1.0
     */
    public function delete(LuxuryTaxInterface $entry): bool;

    /**
     * @param int $entryId
     * @return bool
     * @throws CouldNotDeleteException
     * @since 100.1.0
     */
    public function deleteById(int $entryId): bool;


}
