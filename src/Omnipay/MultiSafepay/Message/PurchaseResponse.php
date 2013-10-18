<?php

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return isset($this->data->transaction->id) ? (string) $this->data->transaction->id : null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data->transaction->payment_url) || isset($this->data->gatewayinfo->redirecturl);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        if (isset($this->data->gatewayinfo->redirecturl)) {
            return (string) $this->data->gatewayinfo->redirecturl;
        } elseif (isset($this->data->transaction->payment_url)) {
            return (string) $this->data->transaction->payment_url;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }
}
