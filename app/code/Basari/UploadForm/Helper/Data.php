<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Basari\UploadForm\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $getSalableQuantityDataBySku;

     public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku

    ) {
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        parent::__construct($context);
    }

    public function getQty($productSku, $websiteId = null)
    {
        $qty = $this->getSalableQuantityDataBySku->execute($productSku);
        return isset($qty[0]['qty']) ? $qty[0]['qty'] : 1;
    }
}
