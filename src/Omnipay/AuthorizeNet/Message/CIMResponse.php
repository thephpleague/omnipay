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
        return $this->data->messages->message->text;
    }

    public function getCode()
    {
        return $this->data->messages->message->code;
    }

    public function getCustomerProfileId()
    {
        return $this->data->customerProfileId;
    }

    public function getCustomerPaymentProfileId()
    {
        return $this->data->customerPaymentProfileId;
    }
}
