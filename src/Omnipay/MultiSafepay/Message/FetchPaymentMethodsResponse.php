<?php

namespace Omnipay\MultiSafepay\Message;

class FetchPaymentMethodsResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->gateways);
    }

    /**
     * Return available payment methods as an associative array.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $result = array();

        foreach ($this->data->gateways->gateway as $gateway) {
            $result[(string) $gateway->id] = (string) $gateway->description;
        }

        return $result;
    }
}
