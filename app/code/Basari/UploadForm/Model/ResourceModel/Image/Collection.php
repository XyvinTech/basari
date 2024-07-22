<?php
namespace Basari\UploadForm\Model\ResourceModel\Image;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'product_upload_file_collection';
	protected $_eventObject = 'image_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Basari\UploadForm\Model\Image', 'Basari\UploadForm\Model\ResourceModel\Image');
	}

}