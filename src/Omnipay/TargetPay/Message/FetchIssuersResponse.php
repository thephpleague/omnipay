<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

class FetchIssuersResponse extends BaseAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Return available issuers as an associative array.
     *
     * @return array
     */
    public function getIssuers()
    {
        $result = array();

        foreach ($this->data as $issuer) {
            $result[(string) $issuer['id']] = (string) $issuer;
        }

        return $result;
    }
}
