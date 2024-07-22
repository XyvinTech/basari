<?php
namespace Sparsh\Banner\Model;

use Sparsh\Banner\Api\BannerImage;
use Sparsh\Banner\Api\Data\BannerInterface;
use Sparsh\Banner\Model\BannerFactory;
use Sparsh\Banner\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class Custom implements BannerImage
{
    protected $objectFactory;
    protected $dataBannerFactory;
    protected $dataObjectHelper;
    protected $dataObjectProcessor;
    protected $collectionFactory;
    public function __construct(
        BannerFactory $objectFactory,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        \Sparsh\Banner\Api\Data\BannerInterfaceFactory $dataBannerFactory,
	   \Sparsh\Banner\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $session
    ) {
        $this->objectFactory        = $objectFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBannerFactory = $dataBannerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
	   $this->bannerCollectionFactory = $bannerCollectionFactory;
       $this->_customerSession=$session;
       $this->_storeManager = $storeManager;
    }

   public function getBybanner()
    {
	$bannerArray=array();
        $bannerCollection = $this->bannerCollectionFactory->create()->addFilter('is_active', 1)
                ->addFieldToFilter('store', $this->_storeManager->getStore()->getId())
                ->addFieldToFilter('customer', $this->_customerSession->getCustomerGroupId());
       $i=1;
		foreach ($bannerCollection as $banner) {
			$data = $banner->getData();
			$bannerArray[]= $data['banner_image'];
            $i++;
        }
        return array($bannerArray); 
    }
}
