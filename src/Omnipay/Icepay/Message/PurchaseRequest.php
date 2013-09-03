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

use Omnipay\Common\Exception\InvalidResponseException;
use SoapFault;
use stdClass;

class PurchaseRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate(
            'merchantId',
            'secretCode',
            'transactionId',
            'amount',
            'country',
            'currency',
            'clientIp',
            'issuer',
            'language',
            'paymentMethod'
        );

        $data = new stdClass();
        $data->MerchantID = $this->getMerchantId();
        $data->OrderID = $this->getTransactionId();
        $data->Amount = $this->getAmountInteger();
        $data->Description = 'description';
        $data->Reference = null;
        $data->Country = $this->getCountry();
        $data->Currency = $this->getCurrency();
        $data->EndUserIP = $this->getClientIp();
        $data->Issuer = $this->getIssuer();
        $data->Language = $this->getLanguage();
        $data->PaymentMethod = $this->getPaymentMethod();
        $data->Timestamp = (null !== $this->getTimestamp()) ? $this->getTimestamp() : gmdate("Y-m-d\TH:i:s\Z");
        $data->URLCompleted = $this->getReturnUrl();
        $data->URLError = $this->getCancelUrl();
        $data->Checksum = $this->generateSignature($data);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        try {
            $rawResponse = $this->getSoapClient()->Checkout(array(
                'request' => $this->getData(),
            ));
        } catch (SoapFault $e) {
            throw new InvalidResponseException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->response = new PurchaseResponse($this, $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature(stdClass $data)
    {
        $raw = implode(
            '|',
            array(
                $this->getSecretCode(),
                $data->MerchantID,
                $data->Timestamp,
                $data->Amount,
                $data->Country,
                $data->Currency,
                $data->Description,
                $data->EndUserIP,
                $data->Issuer,
                $data->Language,
                $data->OrderID,
                $data->PaymentMethod,
                $data->Reference,
                $data->URLCompleted,
                $data->URLError,
            )
        );

        return sha1($raw);
    }
}
