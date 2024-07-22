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

namespace Mageplaza\StoreSwitcher\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Countries
 * @package Mageplaza\StoreSwitcher\Ui\Component\Listing\Columns
 */
class Countries extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['countries'] === null) {
                    $item[$this->getData('name')] = __('No country selected');
                } else {
                    $countries = explode(',', $item['countries']);

                    if (($key = array_search('', $countries)) !== false) {
                        unset($countries[$key]);
                    }

                    $item[$this->getData('name')] = __('A total of %1 countries', count($countries));
                }
            }
        }

        return $dataSource;
    }
}
