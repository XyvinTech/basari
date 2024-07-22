<?php
namespace Basari\UploadForm\Controller\Adminhtml\Filelist;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Basari_UploadForm::filelist');
        $resultPage->getConfig()->getTitle()->prepend(__('Uploaded Files'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }

}