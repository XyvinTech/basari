<?php
namespace Basari\UploadForm\Model;
class Image extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'product_upload_file';

	protected $_cacheTag = 'product_upload_file';

	protected $_eventPrefix = 'product_upload_file';

	protected function _construct()
	{
		$this->_init('Basari\UploadForm\Model\ResourceModel\Image');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}