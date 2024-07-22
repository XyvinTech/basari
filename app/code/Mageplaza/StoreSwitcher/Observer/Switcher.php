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

namespace Mageplaza\StoreSwitcher\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreIsInactiveException;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\StoreSwitcher\Helper\Data as HelperData;
use Mageplaza\StoreSwitcher\Model\Config\Source\ActionType;
use Mageplaza\StoreSwitcher\Model\Config\Source\ChangeType;
use Mageplaza\StoreSwitcher\Model\Rule;

/**
 * Class Switcher
 * @package Mageplaza\StoreSwitcher\Observer
 */
class Switcher implements ObserverInterface
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManagerInterface;

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * Switcher constructor.
     *
     * @param StoreManagerInterface $storeManagerInterface
     * @param HelperData $helperData
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        StoreManagerInterface $storeManagerInterface,
        HelperData $helperData,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->_storeManagerInterface = $storeManagerInterface;
        $this->_helperData            = $helperData;
        $this->storeRepository        = $storeRepository;
    }

    /**
     * @param Observer $observer
     *
     * @throws NoSuchEntityException
     * @throws StoreIsInactiveException
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function execute(Observer $observer)
    {
        if (!$this->_helperData->isEnabled()) {
            return;
        }
        $rule = $this->_helperData->getMatchingRule();

        if (!$rule || ($rule->getFirstRedirect() && $this->_helperData->getCookieByName('first_redirect'))) {
            $this->processSaveStore($observer);

            return;
        }

        /** @var Rule $rule */
        if ($rule->getRedirectType() === ActionType::REDIRECT_STORE_CURRENCY) {
            $fromStore         = $observer->getRequest()->getParam('___from_store');
            $toStore           = $observer->getRequest()->getParam('___store');
            $storeSwitcherCode = $this->_storeManagerInterface->getStore($rule->getStoreRedirected())->getCode();
            $changeStore       = $this->_helperData->getCookieByName('change_store') ?: 0;
            $currentStoreCode  = $this->_storeManagerInterface->getStore()->getCode();

            if ($fromStore && $toStore) {
                if ($fromStore === $storeSwitcherCode) {
                    $currentStoreCode = $fromStore;
                } else {
                    $currentStoreCode = $toStore;
                }
                $this->_helperData->setCookie('switch_store', 1, 120);
            }

            $switch = $this->_helperData->getCookieByName('switch_store') ?: 0;
            if ($changeStore && !$switch && $storeSwitcherCode !== $currentStoreCode) {
                $changeStore = 0;
            }

            if ($changeStore === 0 && $storeSwitcherCode === $currentStoreCode) {
                $changeStore = 1;
            }

            if ($rule->getChangeType() === ChangeType::MANUALLY) {
                $this->_helperData->deleteCookieByName('change_store');
                if ($storeSwitcherCode !== $currentStoreCode) {
                    $currentStore = $this->_helperData->getCookieByName('current_store');
                    if (!$currentStore || ($currentStore && $currentStore !== $currentStoreCode)) {
                        $this->_helperData->setCookie('first_redirect', 0);
                        $this->_helperData->setCookie('current_store', $currentStoreCode, 3600);
                        $this->_helperData->deleteCookieByName('is_switcher');
                    }

                    $store  = $this->storeRepository->getActiveStoreByCode($storeSwitcherCode);
                    $url    = $this->_helperData->getTargetStoreRedirectUrl($store);
                    $label1 = __(
                        'You are in %1, would you like to switch to the %2?',
                        $this->_helperData->getStore()->getName(),
                        $this->_helperData->getStore($rule->getStoreRedirected())->getName()
                    );
                    $label2 = __(
                        'Switch to %1',
                        $this->_helperData->getStore($rule->getStoreRedirected())->getName()
                    );

                    $this->_helperData->setCookie('switch_url', $url, 60);
                    $this->_helperData->setCookie('switch_code', $storeSwitcherCode, 60);
                    $this->_helperData->setCookie('switch_label1', $label1, 60);
                    $this->_helperData->setCookie('switch_label2', $label2, 60);
                }
            } elseif ($changeStore === 0 && $rule->getChangeType() === ChangeType::AUTOMATIC) {
                $store = $this->storeRepository->getActiveStoreByCode($storeSwitcherCode);
                $url   = $this->_helperData->getTargetStoreRedirectUrl($store);
                $observer->getResponse()->setRedirect($url);
                $this->_helperData->setCookie('first_redirect', 1);
                $this->_helperData->setCookie('change_store', 1);
                $this->_helperData->deleteCookieByName('is_switcher');
            }
        } elseif ($rule->getRedirectUrl() && $rule->getRedirectType() === ActionType::REDIRECT_URL) {
            $url        = $rule->getRedirectUrl();
            $currentUrl = $this->_storeManagerInterface->getStore()->getCurrentUrl(false);

            if ($url !== $currentUrl) {
                $switchCookie = $this->_helperData->getCookieByName('switch_store_url');
                if ($rule->getPageType() === 'all_pages') {
                    if (!$switchCookie) {
                        $this->_helperData->setCookie('switch_store_url', 1, 86400);
                        $observer->getResponse()->setRedirect($url);
                    }
                } else {
                    $observer->getResponse()->setRedirect($url);
                }
            } else {
                $this->processSaveStore($observer);
                return;
            }
        }
    }

    /**
     * Process save store function
     *
     * @param Observer $observer
     *
     * @throws NoSuchEntityException
     * @throws StoreIsInactiveException
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function processSaveStore($observer)
    {
        if (!$this->_helperData->getSaveSwitchedStoreConfig()) {
            return;
        }

        $lastStoreCode = $this->_helperData->getCookieByName('mpstoreswitcher_last_code');
        $noteSave      = $this->_helperData->getCookieByName('not_save');
        $stopRedirect  = $this->_helperData->getCookieByName('stop_redirect');

        if ($lastStoreCode && !$noteSave && !$stopRedirect) {
            $store = $this->storeRepository->getActiveStoreByCode($lastStoreCode);
            $url   = $this->_helperData->getTargetStoreRedirectUrl($store);
            $this->_helperData->setCookie('stop_redirect', 1);

            $observer->getResponse()->setRedirect($url);
        }
    }
}
