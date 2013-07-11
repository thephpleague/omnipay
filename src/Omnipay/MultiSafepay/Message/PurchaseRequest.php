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

use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

class PurchaseRequest extends AbstractRequest
{
    protected $userAgent = 'Omnipay';
    protected $liveEndpoint = 'https://api.multisafepay.com/ewx/';
    protected $testEndpoint = 'https://testapi.multisafepay.com/ewx/';

    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    public function getSiteCode()
    {
        return $this->getParameter('siteCode');
    }

    public function setSiteCode($value)
    {
        return $this->setParameter('siteCode', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('transactionId', 'amount', 'currency', 'description', 'clientIp', 'card');

        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><redirecttransaction/>');
        $data->addAttribute('ua', $this->userAgent);

        $merchant = $data->addChild('merchant');
        $merchant->addChild('account', $this->getAccountId());
        $merchant->addChild('site_id', $this->getSiteId());
        $merchant->addChild('site_secure_code', $this->getSiteCode());

        $customer = $data->addChild('customer');
        $customer->addChild('ipaddress', $this->getClientIp());
        $customer->addChild('email', $this->getCard()->getEmail());

        $transaction = $data->addChild('transaction');
        $transaction->addChild('id', $this->getTransactionId());
        $transaction->addChild('currency', $this->getCurrency());
        $transaction->addChild('amount', $this->getAmountInteger());
        $transaction->addChild('description', $this->getDescription());

        $data->addChild('signature', $this->generateSignature());

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

        return $this->response = new PurchaseResponse($this, $httpResponse->xml());
    }

    /**
     * @return string
     */
    protected function generateSignature()
    {
        return md5(
            $this->getAmount().$this->getCurrency().$this->getAccountId().$this->getSiteId().$this->getTransactionId()
        );
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'User-Agent' => $this->userAgent,
        );
    }
}
