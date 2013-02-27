<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PaymentExpress PxPay Authorize Response
 */
class PxPayAuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return 1 === (int) $this->data['valid'];
    }

    public function getTransactionReference()
    {
        return null;
    }

    public function getMessage()
    {
        if (!$this->isRedirect()) {
            return (string) $this->data->URI;
        }
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return (string) $this->data->URI;
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
