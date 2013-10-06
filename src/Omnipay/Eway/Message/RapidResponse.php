<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * eWAY Rapid 3.0 Purchase Response
 */
class RapidResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['TransactionStatus']) && $this->data['TransactionStatus'];
    }

    public function isRedirect()
    {
        return isset($this->data['FormActionURL']);
    }

    public function getRedirectUrl()
    {
        return isset($this->data['FormActionURL']) ? $this->data['FormActionURL'] : null;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'EWAY_ACCESSCODE' => $this->data['AccessCode'],
            );
        }
    }

    public function getTransactionReference()
    {
        return isset($this->data['TransactionID']) ? (string) $this->data['TransactionID'] : null;
    }

    public function getMessage()
    {
        return $this->getCode();
    }

    public function getCode()
    {
        if (!empty($this->data['ResponseMessage'])) {
            return $this->data['ResponseMessage'];
        } elseif (!empty($this->data['Errors'])) {
            return $this->data['Errors'];
        }
    }
}
