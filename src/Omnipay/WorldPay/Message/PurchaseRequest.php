<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * WorldPay Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://secure.worldpay.com/wcc/purchase';
    protected $testEndpoint = 'https://secure-test.worldpay.com/wcc/purchase';

    public function getInstallationId()
    {
        return $this->getParameter('installationId');
    }

    public function setInstallationId($value)
    {
        return $this->setParameter('installationId', $value);
    }

    public function getSecretWord()
    {
        return $this->getParameter('secretWord');
    }

    public function setSecretWord($value)
    {
        return $this->setParameter('secretWord', $value);
    }

    public function getCallbackPassword()
    {
        return $this->getParameter('callbackPassword');
    }

    public function setCallbackPassword($value)
    {
        return $this->setParameter('callbackPassword', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['instId'] = $this->getInstallationId();
        $data['cartId'] = $this->getTransactionId();
        $data['desc'] = $this->getDescription();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['testMode'] = $this->getTestMode() ? 100 : 0;
        $data['MC_callback'] = $this->getReturnUrl();

        if ($this->getCard()) {
            $data['name'] = $this->getCard()->getName();
            $data['address1'] = $this->getCard()->getAddress1();
            $data['address2'] = $this->getCard()->getAddress2();
            $data['town'] = $this->getCard()->getCity();
            $data['region'] = $this->getCard()->getState();
            $data['postcode'] = $this->getCard()->getPostcode();
            $data['country'] = $this->getCard()->getCountry();
            $data['tel'] = $this->getCard()->getPhone();
            $data['email'] = $this->getCard()->getEmail();
        }

        if ($this->getSecretWord()) {
            $data['signatureFields'] = 'instId:amount:currency:cartId';
            $signature_data = array($this->getSecretWord(),
                $data['instId'], $data['amount'], $data['currency'], $data['cartId']);
            $data['signature'] = md5(implode(':', $signature_data));
        }

        return $data;
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
