<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Sparsh\Banner\Api;

/**
 * Company CRUD interface.
 * @api
 */
interface BannerImage
{
    /**
     * Post Company.
     *
     * @api
     * @param string[] $companyDetails
     * @param string[] $customerDetails
     * @param string[] $customerAddress
     * @return string[]
     */
    public function getBybanner();
}
