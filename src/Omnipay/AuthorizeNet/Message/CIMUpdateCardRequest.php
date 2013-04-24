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
 * Authorize.Net CIM Update Card Request
 */
class CIMUpdateCardRequest extends CIMAbstractRequest
{
    protected $requestType = 'updateCustomerPaymentProfileRequest';

    public function getData()
    {
        $this->validate('customerProfileId', 'customerPaymentProfileId', 'card');
        $this->getCard()->validate();
        
        // If you use liveMode and you do not include the ccAddress
        // (billTo address in AuthorizeNet world) or ccZip (billTo zip),
        // you will receive the following error:
        // There was an error processing the transaction
        // or
        // There is one or more missing or invalid required fields
        $data = $this->getBillingData();
        $data->paymentProfile->customerPaymentProfileId = $this->getCustomerPaymentProfileId();

        if ($this->getTestMode()) {
            $data->validationMode = 'testMode';
        }

        return $data;
    }
}
