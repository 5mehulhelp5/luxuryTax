<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Model;

use Andriy\LuxuryTax\Api\Data;
use Andriy\LuxuryTax\Api\LuxuryTaxRepositoryInterface;
use Andriy\LuxuryTax\Model\ResourceModel\Entry;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class EntryRepository implements LuxuryTaxRepositoryInterface
{
    /**
     * @var Entry
     */
    protected Entry $resource;

    /**
     * @var EntryFactory
     */
    protected EntryFactory $entryFactory;

    /**
     * PointsRepository constructor.
     * @param Points $resource
     * @param PointsFactory $pointsFactory
     */
    public function __construct(
        Entry $resource,
        EntryFactory $entryFactory
    ) {
        $this->entryFactory = $entryFactory;
        $this->resource = $resource;
    }

    /**
     * @param Data\LuxuryTaxInterface $entry
     * @return Data\LuxuryTaxInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\LuxuryTaxInterface $entry): Data\LuxuryTaxInterface
    {
        try {
            /** @noinspection PhpParamsInspection */
            $this->resource->save($entry);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $point;
    }

    /**
     * @param int $entryId
     * @return array
     * @throws NoSuchEntityException
     */
    public function get(int $entryId): array
    {
        $entry = $this->entryFactory->create();

        $entry->load($entryId);
        if (!$entry->getId()) {
            throw new NoSuchEntityException(__('Point with id "%1" does not exist.', $entryId));
        }
//        die(var_dump($entry->getData()));
        return $entry->getData();
    }

    /**
     * @param Data\PointsInterface $point
     * @return bool
     */
    public function delete(Data\LuxuryTaxInterface $entry): bool
    {
        try {
            $this->resource->delete($entry);
        } catch (\Exception $exception) {
            /** @noinspection PhpUndefinedClassInspection */
            throw new CouldNotDeleteException(__(
                'Could not delete the Point: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @param int $pointId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById(int $pointId): bool
    {
        return $this->delete($this->get($pointId));
    }
}
