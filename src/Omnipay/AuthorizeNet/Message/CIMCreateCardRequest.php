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
 * Authorize.Net CIM Create Card Request
 */
class CIMCreateCardRequest extends CIMAbstractRequest
{
    protected $requestType = 'createCustomerPaymentProfileRequest';

    public function getData()
    {
        $this->validate('customerProfileId', 'card');
        $this->getCard()->validate();
        
        $data = $this->getBillingData();

        if ($this->getTestMode()) {
            $data->validationMode = 'testMode';
        }

        return $data;
    }
}
