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

use DOMDocument;
use DOMElement;
use DOMXPath;
use Omnipay\Common\Message\AbstractResponse;

class FetchPaymentMethodsResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return !isset($this->data->children('s', true)->Body->Fault);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        if (!$this->isSuccessful()) {
            return (string) $this->data->children('s', true)->Body->Fault->children()->faultcode;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return (string) $this->data->children('s', true)->Body->Fault->children()->faultstring;
        }

        return null;
    }

    /**
     * Return available payment methods, and its issuers as
     * an associative array.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = $this->data
            ->children('s', true)
            ->Body
            ->children()
            ->GetMyPaymentMethodsResponse
            ->GetMyPaymentMethodsResult
            ->children('a', true)
            ->PaymentMethods
            ->children('b', true);

        $data = array();

        foreach ($paymentMethods as $rawPaymentMethod) {
            $paymentMethod = array(
                'Description' => (string)$rawPaymentMethod->Description,
            );

            foreach ($rawPaymentMethod->Issuers->Issuer as $rawIssuer) {
                $issuer = array(
                    'Description' => (string)$rawIssuer->Description,
                );

                foreach ($rawIssuer->Countries->Country as $rawCountry) {
                    $country = array(
                        'Currency' => (string)$rawCountry->Currency,
                        'MaximumAmount' => (string)$rawCountry->MaximumAmount,
                        'MinimumAmount' => (string)$rawCountry->MinimumAmount,
                    );

                    $issuer['Countries'][(string) $rawCountry->CountryCode] = $country;
                }

                $paymentMethod['Issuers'][(string) $rawIssuer->IssuerKeyword] = $issuer;
            }

            $data[(string) $rawPaymentMethod->PaymentMethodCode] = $paymentMethod;
        }

        return $data;
    }
}
