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
 * Authorize.Net CIM Delete Delete Request
 */
class CIMDeleteProfileRequest extends CIMAbstractRequest
{
    protected $requestType = 'deleteCustomerProfileRequest';

    public function getData()
    {
        $this->validate('customerProfileId');
        
        $data = $this->getBaseData();

        $data->customerProfileId = $this->getCustomerProfileId();

        return $data;
    }
}
