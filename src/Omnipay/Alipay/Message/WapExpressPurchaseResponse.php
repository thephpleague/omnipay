<?php

namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class WapExpressPurchaseResponse
 *
 * @package Omnipay\Alipay\Message
 */
class WapExpressPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        if ($this->getRedirectMethod() == 'GET') {
            return $this->getRequest()->getEndpoint() . '?' . http_build_query($this->getRedirectData());
        } else {
            return $this->getRequest()->getEndpoint();
        }
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
