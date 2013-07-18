<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchPaymentMethodsResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->GetMyPaymentMethodsResult->PaymentMethods->PaymentMethod);
    }

    /**
     * Return available payment methods, and its issuers as
     * an associative array.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $result = array();

        foreach ($this->toArray($this->data->GetMyPaymentMethodsResult->PaymentMethods->PaymentMethod) as $paymentMethod) {
            $result[$paymentMethod->PaymentMethodCode] = array(
                'description' => $paymentMethod->Description,
                'issuers' => $this->extractIssuers($paymentMethod),
            );
        }

        return $result;
    }

    /**
     * Convert given data to array if it isn't already one.
     *
     * @param mixed $data
     *
     * @return array
     */
    private function toArray($data)
    {
        if (!is_array($data)) {
            $data = array($data);
        }

        return $data;
    }

    /**
     * @param mixed $paymentMethod
     *
     * @return array
     */
    private function extractIssuers($paymentMethod)
    {
        $issuers = array();

        foreach ($this->toArray($paymentMethod->Issuers->Issuer) as $issuer) {
            $issuers[$issuer->IssuerKeyword] = array(
                'description' => $issuer->Description,
                'countries' => $this->extractCountries($issuer),
            );
        }

        return $issuers;
    }

    /**
     * @param mixed $issuer
     *
     * @return array
     */
    private function extractCountries($issuer)
    {
        $countries = array();

        foreach ($this->toArray($issuer->Countries->Country) as $country) {
            $countries[$country->CountryCode] = array(
                'currency' => $country->Currency,
                'maxAmount' => $country->MaximumAmount,
                'minAmount' => $country->MinimumAmount,
            );
        }

        return $countries;
    }
}
