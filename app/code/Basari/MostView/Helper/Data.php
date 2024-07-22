<?php
namespace Basari\MostView\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{    
    protected $_productCollectionFactory;
        
    public function __construct(        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Registry $registry
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_registry = $registry;    
    }
    
    public function getMostPopularProduct()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('most_popular', 1);
        $collection->setPageSize(16);
        return $collection;
    }

    // public function getMostPopularProduct()
    // {
    //    $mostPopularProduct = $this->getProductCollection()->getMostPopular();
    //    return $mostPopularProduct;
    // }

    public function getMostRatedProduct()
    {
        $mostRatedProduct = $this->_productCollectionFactory->create();
        $mostRatedProduct->addAttributeToSelect('*');
        $mostRatedProduct->addAttributeToSort('updated_at', 'DESC');
        $mostRatedProduct->addFieldToFilter('most_rated', 1);
        $mostRatedProduct->setPageSize(16);
        return $mostRatedProduct;
    }

    public function formatPrice($price, $addBrackets = false)
    {
        return true;
        //return $this->formatPricePrecision($price, 2, $addBrackets);
    }

    public function getCurrentProduct()
    {        
        return $this->_registry->registry('current_product');
    }   
}
?>