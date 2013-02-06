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

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchaseRequest($request, $source);
        $response = $this->send('/Netaxept/Register.aspx', $data);

        $redirectData = array(
            'merchantId' => $this->username,
            'transactionId' => (string) $response->TransactionId,
        );

        return new RedirectResponse(
            $this->getCurrentEndpoint().'/Terminal/Default.aspx?'.http_build_query($redirectData)
        );
    }

    public function completePurchase(Request $request)
    {
        $responseCode = $this->httpRequest->get('responseCode');
        if (empty($responseCode)) {
            throw new InvalidResponseException;
        } elseif ($responseCode != 'OK') {
            throw new Exception($responseCode);
        }

        $data = array(
            'merchantId' => $this->username,
            'token' => $this->password,
            'transactionId' => $this->httpRequest->get('transactionId'),
            'operation' => 'AUTH',
        );

        $response = $this->send('/Netaxept/Process.aspx', $data);

        return new Response($response);
    }

    protected function buildPurchaseRequest(Request $request, $source)
    {
        $request->validateRequired(array('amount', 'returnUrl'));

        $data = array();
        $data['merchantId'] = $this->username;
        $data['token'] = $this->password;
        $data['serviceType'] = 'B';
        $data['orderNumber'] = $request->orderId;
        $data['currencyCode'] = $request->currency;
        $data['amount'] = $request->amount;
        $data['redirectUrl'] = $request->returnUrl;
        $data['customerFirstName'] = $source->getFirstName();
        $data['customerLastName'] = $source->getLastName();
        $data['customerEmail'] = $source->getEmail();
        $data['customerPhoneNumber'] = $source->getPhone();
        $data['customerAddress1'] = $source->getAddress1();
        $data['customerAddress2'] = $source->getAddress2();
        $data['customerPostcode'] = $source->getPostcode();
        $data['customerTown'] = $source->getCity();
        $data['customerCountry'] = $source->getCountry();

        return $data;
    }

    protected function send($url, $data)
    {
        $response = $this->httpClient->get($this->getCurrentEndpoint().$url.'?'.http_build_query($data));

        $xml = new SimpleXMLElement($response);
        if (empty($xml)) {
            throw new InvalidResponseException;
        }
        if (isset($xml->Error) AND isset($xml->Error->Message)) {
            throw new Exception((string) $xml->Error->Message);
        }
        if (empty($xml->TransactionId)) {
            throw new InvalidResponseException;
        }

        return $xml;
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
