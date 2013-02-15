<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress;

use SimpleXMLElement;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\RedirectResponse;
use Omnipay\Common\Request;

/**
 * DPS PaymentExpress PxPay Gateway
 */
class PxPayGateway extends AbstractGateway
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxpay/pxaccess.aspx';
    protected $username;
    protected $password;

    public function getName()
    {
        return 'PaymentExpress PxPay';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
        );
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function authorize($options)
    {
        $data = $this->buildPurchase($options);
        $data->TxnType = 'Auth';

        return $this->sendPurchase($data);
    }

    public function completeAuthorize($options)
    {
        return $this->completePurchase($options);
    }

    public function purchase($options)
    {
        $data = $this->buildPurchase($options);
        $data->TxnType = 'Purchase';

        return $this->sendPurchase($data);
    }

    public function completePurchase($options)
    {
        $result = $this->httpRequest->get('result');
        if (empty($result)) {
            throw new InvalidResponseException;
        }

        // validate dps response
        $data = new SimpleXMLElement('<ProcessResponse/>');
        $data->PxPayUserId = $this->username;
        $data->PxPayKey = $this->password;
        $data->Response = $result;

        return $this->sendComplete($data);
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));

        $data = new SimpleXMLElement('<GenerateRequest/>');
        $data->PxPayUserId = $this->username;
        $data->PxPayKey = $this->password;
        $data->AmountInput = $request->getAmountDecimal();
        $data->CurrencyInput = $request->getCurrency();
        $data->MerchantReference = $request->getDescription();
        $data->UrlSuccess = $request->getReturnUrl();
        $data->UrlFail = $request->getReturnUrl();

        return $data;
    }

    protected function sendPurchase($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data->asXML())->send();
        $xml = new SimpleXMLElement($httpResponse->getBody());

        if ((string) $xml['valid'] == '1') {
            return new RedirectResponse((string) $xml->URI);
        } else {
            throw new InvalidResponseException;
        }
    }

    protected function sendComplete($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data->asXML())->send();

        return new Response($httpResponse->getBody());
    }
}
