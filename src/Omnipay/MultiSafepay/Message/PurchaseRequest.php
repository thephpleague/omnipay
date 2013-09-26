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

use Omnipay\Common\CreditCard;
use SimpleXMLElement;

class PurchaseRequest extends AbstractRequest
{
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getGateway()
    {
        return $this->getParameter('gateway');
    }

    public function setGateway($value)
    {
        return $this->setParameter('gateway', $value);
    }

    public function getIssuer()
    {
        return $this->getParameter('issuer');
    }

    public function setIssuer($value)
    {
        return $this->setParameter('issuer', $value);
    }

    public function getGoogleAnalyticsCode()
    {
        return $this->getParameter('googleAnalyticsCode');
    }

    public function setGoogleAnalyticsCode($value)
    {
        return $this->setParameter('googleAnalyticsCode', $value);
    }

    public function getExtraData1()
    {
        return $this->getParameter('extraData1');
    }

    public function setExtraData1($value)
    {
        return $this->setParameter('extraData1', $value);
    }

    public function getExtraData2()
    {
        return $this->getParameter('extraData2');
    }

    public function setExtraData2($value)
    {
        return $this->setParameter('extraData2', $value);
    }

    public function getExtraData3()
    {
        return $this->getParameter('extraData3');
    }

    public function setExtraData3($value)
    {
        return $this->setParameter('extraData3', $value);
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
        $merchant->addChild('notification_url', htmlspecialchars($this->getNotifyUrl()));
        $merchant->addChild('cancel_url', htmlspecialchars($this->getCancelUrl()));
        $merchant->addChild('redirect_url', htmlspecialchars($this->getReturnUrl()));
        $merchant->addChild('gateway', $this->getGateway());

        if ('IDEAL' === $this->getGateway() && $this->getIssuer()) {
            $gatewayInfo = $data->addChild('gatewayinfo');
            $gatewayInfo->addChild('issuerid', $this->getIssuer());
        }

        /** @var CreditCard $card */
        $card = $this->getCard();
        $customer = $data->addChild('customer');
        $customer->addChild('ipaddress', $this->getClientIp());
        $customer->addChild('locale', $this->getLanguage());
        $customer->addChild('email', $card->getEmail());
        $customer->addChild('firstname', $card->getFirstName());
        $customer->addChild('lastname', $card->getLastName());
        $customer->addChild('address1', $card->getAddress1());
        $customer->addChild('address2', $card->getAddress2());
        $customer->addChild('zipcode', $card->getPostcode());
        $customer->addChild('city', $card->getCity());
        $customer->addChild('country', $card->getCountry());
        $customer->addChild('phone', $card->getPhone());

        $data->addChild('google_analytics', $this->getGoogleAnalyticsCode());

        $transaction = $data->addChild('transaction');
        $transaction->addChild('id', $this->getTransactionId());
        $transaction->addChild('currency', $this->getCurrency());
        $transaction->addChild('amount', $this->getAmountInteger());
        $transaction->addChild('description', $this->getDescription());
        $transaction->addChild('var1', $this->getExtraData1());
        $transaction->addChild('var2', $this->getExtraData2());
        $transaction->addChild('var3', $this->getExtraData3());

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
            $this->getAmountInteger().
            $this->getCurrency().
            $this->getAccountId().
            $this->getSiteId().
            $this->getTransactionId()
        );
    }
}
