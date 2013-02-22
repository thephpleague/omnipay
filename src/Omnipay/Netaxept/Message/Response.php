<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Netaxept Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $endpoint;
    protected $merchantId;

    public function isSuccessful()
    {
        return isset($this->data->ResponseCode) && 'OK' === (string) $this->data->ResponseCode;
    }

    public function isRedirect()
    {
        return !$this->isSuccessful() && 'RegisterResponse' === (string) $this->data->getName();
    }

    public function getGatewayReference()
    {
        return isset($this->data->TransactionId) ? (string) $this->data->TransactionId : null;
    }

    public function getMessage()
    {
        if (isset($this->data->Error->Message)) {
            return (string) $this->data->Error->Message;
        } elseif (isset($this->data->ResponseCode)) {
            return (string) $this->data->ResponseCode;
        }
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            $data = array(
                'merchantId' => $this->merchantId,
                'transactionId' => $this->getGatewayReference(),
            );

            return $this->endpoint.'/Terminal/Default.aspx?'.http_build_query($data);
        }
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setEndpoint($value)
    {
        $this->endpoint = $value;

        return $this;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;

        return $this;
    }
}
