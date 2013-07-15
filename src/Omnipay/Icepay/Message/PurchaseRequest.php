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

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://pay.icepay.eu/Checkout.aspx';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecretCode()
    {
        return $this->getParameter('secretCode');
    }

    public function setSecretCode($value)
    {
        return $this->setParameter('secretCode', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    public function getIssuer()
    {
        return $this->getParameter('issuer');
    }

    public function setIssuer($value)
    {
        return $this->setParameter('issuer', $value);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = array(
            'IC_Merchant' => $this->getMerchantId(),
            'IC_Amount' => $this->getAmountInteger(),
            'IC_Currency' => $this->getCurrency(),
            'IC_OrderID' => $this->getTransactionId(),
            'IC_PaymentMethod' => $this->getPaymentMethod(),
            'IC_Issuer' => $this->getIssuer(),
            'IC_CheckSum' => $this->generateSignature(),
        );

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    /**
     * @return string
     */
    protected function generateSignature()
    {
        return sha1(
            implode('|', array(
                    $this->getSecretCode(),
                    $this->getMerchantId(),
                    $this->getAmountInteger(),
                    $this->getCurrency(),
                    $this->getTransactionId(),
                    $this->getPaymentMethod(),
                    $this->getIssuer(),
                )
            )
        );
    }
}
