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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Authorize.Net CIM Response
 */
class CIMResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return 'Ok' === (string) $this->data->messages->resultCode;
    }

    public function getMessage()
    {
        if ($this->getDirectResponse()) {
            $directResponse = $this->getDirectResponse();
            
            return $directResponse[3];
        }
        
        return (string) $this->data->messages->message->text;
    }

    public function getCode()
    {
        return (string) $this->data->messages->message->code;
    }

    public function getCustomerProfileId()
    {
        return (string) $this->data->customerProfileId;
    }

    public function getCustomerPaymentProfileId()
    {
        return (string) $this->data->customerPaymentProfileId;
    }

    public function getDirectResponse()
    {
        if ($this->data->directResponse) {
            return explode(',', $this->data->directResponse);    
        } elseif ($this->data->validationDirectResponse) {
            return explode(',', $this->data->validationDirectResponse);    
        }
        
        return null;
    }

    public function getTransactionReference()
    {
        if ($this->getDirectResponse()) {
            $directResponse = $this->getDirectResponse();
            
            if ($this->data->validationDirectResponse) {
                return $directResponse[4];
            }

            return $directResponse[6];
        }

        return null;
    }
}
