<?php
namespace Basari\MostView\Block;
use Magento\Framework\View\Element\Template;

class Mostview extends Template
{
    protected $_productsFactory;
    protected $_storeManager;	

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $productsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_productsFactory = $productsFactory;
	    $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
   
    public function getProductCollection()
    {
        $currentStoreId = $this->_storeManager->getStore()->getId();

        $collection = $this->_productsFactory->create()
                           ->addAttributeToSelect('*')
                           ->setStoreId($this->getStoreId())->addViewsCount()
							->addStoreFilter($this->getStoreId())
							->setPageSize($this->getProductsCount());

	    return $collection->getItems();
    }
}