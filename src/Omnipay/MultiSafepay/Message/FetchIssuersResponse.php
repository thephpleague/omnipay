<?php

namespace Omnipay\MultiSafepay\Message;

class FetchIssuersResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->issuers);
    }

    /**
     * Return available issuers as an associative array.
     *
     * @return array
     */
    public function getIssuers()
    {
        $result = array();

        foreach ($this->data->issuers->issuer as $issuer) {
            $result[(string) $issuer->code] = (string) $issuer->description;
        }

        return $result;
    }
}
