<?php
 
namespace Basari\AddressField\Plugin\Checkout;
 
class LayoutProcessorPlugin
{
    public function aroundProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        \Closure $proceed,
        array $jsLayout
    )
    {
        $jsLayoutResult = $proceed($jsLayout);

        $shippingAddress = &$jsLayoutResult['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        unset($shippingAddress['street']['label']);

        // Shipping fields street labels
        $shippingAddress['street']['children']['0']['label'] = __('Address Line 1');
        $shippingAddress['street']['children']['1']['label'] = __('Address Line 2');

        return $jsLayoutResult;
    }
}    