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
 * Authorize.Net CIM Create Profile Request
 */
class CIMCreateProfileRequest extends CIMAbstractRequest
{
    public function getData()
    {
        $this->validate('customerEmail');

        $data = $this->getBaseData();
        
        $data->profile->merchantCustomerId = $this->getCustomerId();
        $data->profile->description = $this->getDescription();
        $data->profile->email = $this->getCustomerEmail();

        return $data;
    }
}
