<?php

/**
 * Product GoogleTagManagerPro UpgradeData
 *
 * Copyright © 2020 Scommerce Mage. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Scommerce\GoogleTagManagerPro\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Config\Model\ResourceModel\Config as StoreConfig;

/**
 * Class UpgradeData
 * @package Scommerce\GoogleTagManagerPro\Setup
 */
class UpgradeData implements UpgradeDataInterface {

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var StoreConfig
     */
    private $config;

    /**
     * @var array
     */
    protected $_gtmConfigKeys = [
        'googletagmanagerpro/general/active'                        => 'scommerce_trackingbase/general/active',
        'googletagmanagerpro/general/enhanced_ecommerce_enabled'    => 'scommerce_trackingbase/general/enhanced_ecommerce_enabled',
        'googletagmanagerpro/general/brand_dropdown'                => 'scommerce_trackingbase/general/brand_dropdown',
        'googletagmanagerpro/general/brand_text'                    => 'scommerce_trackingbase/general/brand_text',
        'googletagmanagerpro/general/category_ajax_enabled'         => 'scommerce_trackingbase/general/category_ajax_enabled',
        'googletagmanagerpro/general/send_impression_on_scroll'     => 'scommerce_trackingbase/general/send_impression_on_scroll',
        'googletagmanagerpro/general/product_item_class'            => 'scommerce_trackingbase/general/product_item_class',
        'googletagmanagerpro/general/scroll_threshold'              => 'scommerce_trackingbase/general/scroll_threshold',
        'googletagmanagerpro/general/base'                          => 'scommerce_trackingbase/general/base',
        'googletagmanagerpro/general/attribute_key'                 => 'scommerce_trackingbase/general/attribute_key',
        'googletagmanagerpro/general/send_admin_orders'             => 'scommerce_trackingbase/general/send_admin_orders',
        'googletagmanagerpro/general/admin_source'                  => 'scommerce_trackingbase/general/admin_source',
        'googletagmanagerpro/general/admin_medium'                  => 'scommerce_trackingbase/general/admin_medium',
        'googletagmanagerpro/general/order_total_include_vat'       => 'scommerce_trackingbase/general/order_total_include_vat',
        'googletagmanagerpro/general/price_including_tax'           => 'scommerce_trackingbase/general/price_including_tax',
        'googletagmanagerpro/general/send_parent_sku'               => 'scommerce_trackingbase/general/send_parent_sku',
        'googletagmanagerpro/general/send_parent_category'          => 'scommerce_trackingbase/general/send_parent_category'
    ];

    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        ResourceConnection $resourceConnection,
        StoreConfig $config
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->resourceConnection = $resourceConnection;
        $this->config = $config;
    }

    /**
     * Upgrade script
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '2.0.37', '<')) {
            $attributeCode = 'product_primary_category';
            /**
             * Add attributes to the eav/attribute
             */
            if (!$this->isProductAttributeExists($attributeCode)) {
                $eavSetup->addAttribute(
                    Product::ENTITY,
                    $attributeCode,
                    [
                        'type' => 'int',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Primary Category',
                        'input' => 'select',
                        'class' => '',
                        'source' => 'Scommerce\GoogleTagManagerPro\Model\Entity\Attribute\Source\Categories',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Primary Category',
                        'visible' => true,
                        'required' => false,
                        'user_defined' => false,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'unique' => false
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '3.0.0') < 0) {
            $connection = $this->resourceConnection->getConnection();
            $table = $this->config->getMainTable();
            foreach ($this->_gtmConfigKeys as $gtmKey => $baseKey) {
                $sql = "select * from " . $table . " where `path`='" . $gtmKey . "'";
                $data = $connection->fetchAll($sql);
                foreach ($data as $configData) {
                    $sqlTest = "select config_id from " . $table . " where path='" . $baseKey . "' and `scope`='" .
                        $configData['scope'] . "' and `scope_id`=" . $configData['scope_id'];
                    $exists = $connection->fetchOne($sqlTest);
                    if (!$exists) {
                        $this->config->saveConfig($baseKey, $configData['value'], $configData['scope'], $configData['scope_id']);
                    }
                }
            }
        }

        $setup->endSetup();
    }

    /**
     * @param $field
     * @return bool
     * @throws LocalizedException
     */
    protected function isProductAttributeExists($field)
    {
        $attr = $this->eavConfig->getAttribute(Product::ENTITY, $field);
        return $attr && $attr->getId();
    }
}
