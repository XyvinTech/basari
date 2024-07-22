<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_StoreSwitcher
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\StoreSwitcher\Controller\Switcher;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\StoreSwitcher\Helper\Data as HelperData;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Class Index
 * @package Mageplaza\StoreSwitcher\Controller\Switcher
 */
class Index extends Action
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     */
    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        StoreManagerInterface $storeManager,
        HelperData $helperData
    ) {
        $this->helperData      = $helperData;
        $this->redirectFactory = $redirectFactory;
        $this->storeManager    = $storeManager;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();

        if ($this->helperData->isEnabled() && $this->getRequest()->getParam('___currency')) {
            $this->storeManager->getStore()->setCurrentCurrencyCode($this->getRequest()->getParam('___currency'));
        }

        return $resultRedirect->setPath('stores/store/redirect', ['_current' => true]);
    }
}
