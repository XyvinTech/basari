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

namespace Mageplaza\StoreSwitcher\Block\Adminhtml\Rule\Edit\Tab;

use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Mageplaza\StoreSwitcher\Model\Config\Source\ActionType;
use Mageplaza\StoreSwitcher\Model\Config\Source\ChangeType;
use Mageplaza\StoreSwitcher\Model\Config\Source\RedirectCurrency;
use Mageplaza\StoreSwitcher\Model\Config\Source\RedirectStore;
use Mageplaza\StoreSwitcher\Model\Rule;

/**
 * Class Actions
 * @package Mageplaza\StoreSwitcher\Block\Adminhtml\Rule\Edit\Tab
 */
class Actions extends Generic implements TabInterface
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'Mageplaza_StoreSwitcher::rule/actions.phtml';

    /**
     * @var ActionType
     */
    protected $_actionType;

    /**
     * @var RedirectStore
     */
    protected $_redirectStore;

    /**
     * @var RedirectCurrency
     */
    protected $_redirectCurrency;

    /**
     * @var ChangeType
     */
    protected $_changeType;

    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var Yesno
     */
    protected $yesNo;

    /**
     * Actions constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param ActionType $actionType
     * @param RedirectStore $redirectStore
     * @param Store $systemStore
     * @param RedirectCurrency $redirectCurrency
     * @param ChangeType $changeType
     * @param Yesno $yesNo
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        ActionType $actionType,
        RedirectStore $redirectStore,
        Store $systemStore,
        RedirectCurrency $redirectCurrency,
        ChangeType $changeType,
        Yesno $yesNo,
        array $data = []
    ) {
        $this->_actionType       = $actionType;
        $this->_redirectStore    = $redirectStore;
        $this->_redirectCurrency = $redirectCurrency;
        $this->_changeType       = $changeType;
        $this->_systemStore      = $systemStore;
        $this->yesNo             = $yesNo;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _prepareForm()
    {
        /** @var Rule $rule */
        $rule = $this->_coreRegistry->registry('mageplaza_storeswitcher_rule');

        /** @var Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('rule_');
        $form->setFieldNameSuffix('rule');
        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('Actions'),
            'class'  => 'fieldset-wide'
        ]);

        $fieldset->addField('redirect_type', 'select', [
            'name'   => 'redirect_type',
            'label'  => __('Type'),
            'title'  => __('Type'),
            'values' => $this->_actionType->toOptionArray()
        ]);

        $fieldset->addField('change_type', 'select', [
            'name'   => 'change_type',
            'label'  => __('How to change Store View'),
            'title'  => __('How to change Store View'),
            'values' => $this->_changeType->toOptionArray(),
            'note'   => __('If Manually is selected, a notice will be shown to ask if the visitor wants to change the store view which suits the current location or not. If Automatically is selected, the appropriate store view will be auto-switched without any advanced notice or permission.')
        ]);

        $fieldset->addField('store_redirected', 'select', [
            'name'   => 'store_redirected',
            'label'  => __('Redirect to a store'),
            'title'  => __('Redirect to a store'),
            'values' => $this->_redirectStore->toOptionArray()
        ]);

        $fieldset->addField('currency', 'select', [
            'name'   => 'currency',
            'label'  => __('Change Currency to'),
            'title'  => __('Change Currency to'),
            'values' => $this->_redirectCurrency->toOptionArray()
        ]);

        $fieldset->addField('first_redirect', 'select', [
            'name'   => 'first_redirect',
            'label'  => __('Redirect Only For The First Access Time'),
            'title'  => __('Redirect Only For The First Access Time'),
            'values' => $this->yesNo->toOptionArray(),
            'note'   => __('If Yes, you will only be able to apply the correct redirect to Store Views / Currency Change once')
        ]);

        $fieldset->addField('redirect_url', 'text', [
            'name'  => 'redirect_url',
            'label' => __('Redirect to a URL'),
            'title' => __('Redirect to a URL'),
            'class' => 'validate-url'
        ]);

        if ($this->_storeManager->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        } else {
            /** @var RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock(Element::class);
            $fieldset->addField('store_ids', 'multiselect', [
                'name'     => 'store_ids',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);

            if (!$rule->hasData('store_ids')) {
                $rule->setStoreIds(0);
            }
        }

        $form->addValues($rule->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Actions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
