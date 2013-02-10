<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Netaxept;

use SimpleXMLElement;
use Tala\AbstractGateway;
use Tala\Exception;
use Tala\Exception\InvalidResponseException;
use Tala\RedirectResponse;
use Tala\Request;

/**
 * CardSave Gateway
 *
 * @link http://www.cardsave.net/dev-downloads
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://epayment.bbs.no';
    protected $testEndpoint = 'https://epayment-test.bbs.no';
    protected $merchantId;
    protected $token;
    protected $testMode;

    public function getName()
    {
        return 'Netaxept';
    }

    public function defineSettings()
    {
        return array(
            'merchantId' => '',
            'token' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function purchase($options)
    {
        $data = $this->buildPurchaseRequest($options);
        $response = $this->send('/Netaxept/Register.aspx', $data);

        if (isset($response->Error)) {
            return new Response($response);
        }

        $redirectData = array(
            'merchantId' => $this->merchantId,
            'transactionId' => (string) $response->TransactionId,
        );

        return new RedirectResponse(
            $this->getCurrentEndpoint().'/Terminal/Default.aspx?'.http_build_query($redirectData)
        );
    }

    public function completePurchase($options)
    {
        $responseCode = $this->httpRequest->get('responseCode');
        if (empty($responseCode)) {
            throw new InvalidResponseException;
        }
        if ('OK' !== $responseCode) {
            return new ErrorResponse($responseCode);
        }

        $data = array(
            'merchantId' => $this->merchantId,
            'token' => $this->token,
            'transactionId' => $this->httpRequest->get('transactionId'),
            'operation' => 'AUTH',
        );

        $response = $this->send('/Netaxept/Process.aspx', $data);

        return new Response($response);
    }

    protected function buildPurchaseRequest($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['merchantId'] = $this->merchantId;
        $data['token'] = $this->token;
        $data['serviceType'] = 'B';
        $data['orderNumber'] = $request->getTransactionId();
        $data['currencyCode'] = $request->getCurrency();
        $data['amount'] = $request->getAmount();
        $data['redirectUrl'] = $request->getReturnUrl();

        $source = $request->getCard();
        if ($source) {
            $data['customerFirstName'] = $source->getFirstName();
            $data['customerLastName'] = $source->getLastName();
            $data['customerEmail'] = $source->getEmail();
            $data['customerPhoneNumber'] = $source->getPhone();
            $data['customerAddress1'] = $source->getAddress1();
            $data['customerAddress2'] = $source->getAddress2();
            $data['customerPostcode'] = $source->getPostcode();
            $data['customerTown'] = $source->getCity();
            $data['customerCountry'] = $source->getCountry();
        }

        return $data;
    }

    protected function send($url, $data)
    {
        $response = $this->httpClient->get($this->getCurrentEndpoint().$url.'?'.http_build_query($data));

        $xml = new SimpleXMLElement($response);
        if (empty($xml)) {
            throw new InvalidResponseException;
        }

        return $xml;
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
