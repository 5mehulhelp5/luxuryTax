<?php
/**
 * Copyright © Andriy Stetsiuk. All rights reserved.
 */
namespace Andriy\LuxuryTax\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCatalogAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $catalogConfig = [
            'quote_item' => [
                'luxury_tax_amount',
                'base_luxury_tax_amount'
            ]
        ];

        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('catalog_eav_attribute'),
            $this->prepareCatalogData($catalogConfig)
        );

        $this->moduleDataSetup->endSetup();
    }

    /**
     * Підготовка даних для вставки
     *
     * @param array $config
     * @return array
     */
    private function prepareCatalogData(array $config): array
    {
        $data = [];
        $attributeIds = $this->getAttributeIds();

        foreach ($config as $entityType => $attributes) {
            foreach ($attributes as $attributeCode) {
                if (isset($attributeIds[$attributeCode])) {
                    $data[] = [
                        'attribute_id' => $attributeIds[$attributeCode],
                        'is_global' => 1,
                        'is_visible' => 0,
                        'is_required' => 0,
                        'used_in_product_listing' => 0,
                        'is_filterable_in_search' => 0
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * Отримання ID атрибутів
     *
     * @return array
     */
    private function getAttributeIds(): array
    {
        $connection = $this->moduleDataSetup->getConnection();

        $select = $connection->select()
            ->from($this->moduleDataSetup->getTable('eav_attribute'), ['attribute_code', 'attribute_id'])
            ->where('attribute_code IN (?)', ['luxury_tax_amount', 'base_luxury_tax_amount']);

        return $connection->fetchPairs($select);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
