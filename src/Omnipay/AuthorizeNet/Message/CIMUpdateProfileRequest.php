<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net CIM Update Profile Request
 */
class CIMUpdateProfileRequest extends CIMAbstractRequest
{
    protected $requestType = 'updateCustomerProfileRequest';

    public function getData()
    {
        $this->validate('customerEmail', 'customerProfileId');
        
        $data = $this->getBaseData();
        
        $data->profile->merchantCustomerId = $this->getCustomerId();
        $data->profile->description = $this->getDescription();
        $data->profile->email = $this->getCustomerEmail();
        $data->profile->customerProfileId = $this->getCustomerProfileId();

        return $data;
    }
}
