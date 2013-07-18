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

class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://pay.icepay.eu/Checkout.aspx';

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'secretCode', 'transactionId', 'amount', 'currency', 'paymentMethod', 'issuer');

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
        $raw = implode(
            '|',
            array(
                $this->getSecretCode(),
                $this->getMerchantId(),
                $this->getAmountInteger(),
                $this->getCurrency(),
                $this->getTransactionId(),
                $this->getPaymentMethod(),
                $this->getIssuer(),
            )
        );

        return sha1($raw);
    }
}
