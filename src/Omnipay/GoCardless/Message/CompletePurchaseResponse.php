<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Exception;

/**
 * GoCardless Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    protected $gatewayReference;

    public function isSuccessful()
    {
        return !isset($this->data['error']);
    }

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }

    public function setGatewayReference($value)
    {
        $this->gatewayReference = $value;

        return $this;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return reset($this->data['error']);
        }
    }
}
