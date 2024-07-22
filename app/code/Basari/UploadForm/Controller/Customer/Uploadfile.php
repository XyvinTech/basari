<?php
namespace Basari\UploadForm\Controller\Customer;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

class Uploadfile extends \Magento\Framework\App\Action\Action
{
    protected $_fileSystem;
    protected $_adapterFactory;
    protected $_uploaderFactory;
    protected $_imageFactory;
    protected $_messageManager;
    protected $_productloader;
    protected $_customerSession;
    protected $allowedExtensions = ['png','jpg','jpeg','pdf','docx', 'doc']; // to allow file upload types
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        \Basari\UploadForm\Model\ImageFactory $imageFactory,
        ManagerInterface $messageManager,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Customer\Model\Session $customerSession,
        AdapterFactory $adapterFactory

    ) {
        parent::__construct($context);
        $this->_fileSystem = $fileSystem;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_imageFactory = $imageFactory;
        $this->_messageManager = $messageManager;
        $this->_productloader = $_productloader;
        $this->_customerSession = $customerSession;
        $this->_adapterFactory = $adapterFactory;
    }

    public function execute()
    {
        $fileup = $this->getRequest()->getFiles('fileInput');
        $proId = $this->getRequest()->getParam('product_id');
        $imageFactoryObject = $this->_imageFactory->create();
        try
        {
            if(empty($fileup['name']))
            {
                $this->_messageManager->addError(
                    __('Please upload file and try again')
                );
            }
            else
            {
                $uploader = $this->_uploaderFactory->create(['fileId' => 'fileInput']);
                $uploader->setAllowedExtensions($this->allowedExtensions);
                $fileAdapter = $this->_adapterFactory->create();
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $mediaDirectory = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA);

                $destinationPath = $mediaDirectory->getAbsolutePath('uploadedFiles');
                $result = $uploader->save($destinationPath);
                if($result)
                {
                    $fileUploaded = $result['file'];
                    $fileParts = explode(".",$fileUploaded);
                    $customerData = $this->getCustomer();
                    $imageData['title'] = $fileParts[0];
                    $imageData['image'] = $fileUploaded;
                    $imageData['customer_name'] = $customerData->getFirstname(). " ".$customerData->getLastname();
                    $imageData['customer_email'] = $customerData->getEmail();
                    $imageFactoryObject->setData($imageData);
                    if($imageFactoryObject->save())
                    {
                        $this->_messageManager->addSuccess(
                            __('Image uploaded successfully.')
                        );
                    }
                }
                else {
                    $this->_messageManager->addError(
                        __('File cannot be saved to path: $1', $destinationPath)
                    );
                }
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError(
                __($e->getMessage())
            );
        }

        $productUrl = $this->getProductUrl($proId);
        return $this->_redirect($productUrl);
    }

    public function getProductUrl($id)
    {
        $currentProduct = $this->_productloader->create()->load($id);
        return $currentProduct->getProductUrl();
    }

    public function getCustomer()
    {
       return $this->_customerSession->getCustomer();
    }
}