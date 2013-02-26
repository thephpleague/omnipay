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
    public function isSuccessful()
    {
        return isset($this->data->ResponseCode) && 'OK' === (string) $this->data->ResponseCode;
    }

    public function isRedirect()
    {
        return !$this->isSuccessful() && 'RegisterResponse' === (string) $this->data->getName();
    }

    public function getTransactionReference()
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
                'merchantId' => $this->getRequest()->getMerchantId(),
                'transactionId' => $this->getTransactionReference(),
            );

            return $this->getRequest()->getEndpoint().'/Terminal/Default.aspx?'.http_build_query($data);
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
}
