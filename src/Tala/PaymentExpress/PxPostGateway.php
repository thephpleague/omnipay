<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PaymentExpress;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * DPS PaymentExpress PxPost Gateway
 */
class PxPostGateway extends AbstractGateway
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxpost.aspx';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
        );
    }

    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'Auth');

        return $this->send($data);
    }

    public function capture(Request $request)
    {
        $data = $this->buildCaptureOrRefund($request, 'Complete');

        return $this->send($data);
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'Purchase');

        return $this->send($data);
    }

    public function refund(Request $request)
    {
        $data = $this->buildCaptureOrRefund($request, 'Refund');

        return $this->send($data);
    }

    protected function buildAuthorizeOrPurchase($request, $source, $method)
    {
        $request->validateRequired(array('amount'));
        $source->validateRequired(array('firstName', 'lastName', 'number', 'expiryMonth', 'expiryYear', 'cvv'));
        $source->validateNumber();

        $data = new \SimpleXMLElement('<Txn />');
        $data->PostUsername = $this->username;
        $data->PostPassword = $this->password;
        $data->TxnType = $method;
        $data->CardNumber = $source->number;
        $data->CardHolderName = $source->name;
        $data->Amount = $request->amountDollars;
        $data->DateExpiry = $source->getExpiryDate('my');
        $data->Cvc2 = $source->cvv;
        $data->InputCurrency = $request->currency;
        $data->MerchantReference = $request->description;

        return $data;
    }

    protected function buildCaptureOrRefund($request, $method)
    {
        $request->validateRequired(array('gatewayReference', 'amount'));

        $data = new \SimpleXMLElement('<Txn />');
        $data->PostUsername = $this->username;
        $data->PostPassword = $this->password;
        $data->TxnType = $method;
        $data->DpsTxnRef = $request->gatewayReference;
        $data->Amount = $request->amountDollars;

        return $data;
    }

    protected function send($data)
    {
        $response = $this->httpClient->post($this->endpoint, $data->asXML());

        return new Response($response);
    }
}
