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

use SimpleXMLElement;

class CompletePurchaseRequest extends PurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('transactionId');

        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><status/>');
        $data->addAttribute('ua', $this->userAgent);

        $merchant = $data->addChild('merchant');
        $merchant->addChild('account', $this->getAccountId());
        $merchant->addChild('site_id', $this->getSiteId());
        $merchant->addChild('site_secure_code', $this->getSiteCode());

        $transaction = $data->addChild('transaction');
        $transaction->addChild('id', $this->getTransactionId());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint(),
            $this->getHeaders(),
            $this->getData()->asXML()
        )->send();

        return $this->response = new CompletePurchaseResponse($this, $httpResponse->xml());
    }
}
