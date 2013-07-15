<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
