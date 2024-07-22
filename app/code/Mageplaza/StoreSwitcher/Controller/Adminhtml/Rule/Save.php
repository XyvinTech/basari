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

namespace Mageplaza\StoreSwitcher\Controller\Adminhtml\Rule;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\StoreSwitcher\Controller\Adminhtml\Rule;
use Mageplaza\StoreSwitcher\Model\Rule as RuleModel;
use Mageplaza\StoreSwitcher\Model\RuleFactory;
use RuntimeException;

/**
 * Class Save
 * @package Mageplaza\StoreSwitcher\Controller\Adminhtml\Rule
 */
class Save extends Rule
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param RuleFactory $ruleFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Registry $registry,
        RuleFactory $ruleFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;

        parent::__construct($ruleFactory, $registry, $context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('rule')) {
            /** @var RuleModel $rule */
            $rule = $this->initRule();
            if (!isset($data['countries'])) {
                $data['countries'] = [];
            }

            $storeIds          = $data['store_ids'];
            $stores            = $this->storeManager->getStores();
            $data['store_ids'] = implode(',', $data['store_ids']);
            if (in_array('0', $storeIds, true)) {
                $data['store_ids'] = implode(',', array_keys($stores));
            }
            $rule->addData($data);
            $this->_eventManager->dispatch(
                'mageplaza_storeswitcher_rule_before_save',
                ['post' => $rule, 'request' => $this->getRequest()]
            );

            try {
                $rule->save();

                $this->messageManager->addSuccessMessage(__('The rule has been saved.'));
                $this->_getSession()->setData('mageplaza_storeswitcher_rule_data', false);

                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('mpstoreswitcher/*/edit', ['id' => $rule->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('mpstoreswitcher/*/');
                }

                return $resultRedirect;
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Rule.'));
            }

            $resultRedirect->setPath('mpstoreswitcher/*/edit', ['id' => $rule->getId(), '_current' => true]);

            return $resultRedirect;
        }

        $resultRedirect->setPath('mpstoreswitcher/*/');

        return $resultRedirect;
    }
}
