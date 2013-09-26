<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\ExpressCompleteAuthorizeRequest;
use Omnipay\TestCase;

class ExpressCompleteAuthorizeRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\ExpressCompleteAuthorizeRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();

        $request = $this->getHttpRequest();
        $request->query->set('PayerID', 'Payer-1234');
        $request->query->set('token', 'TOKEN1234');

        $this->request = new ExpressCompleteAuthorizeRequest($client, $request);
    }

    public function testGetData()
    {
        $this->request->setAmount('1.23');
        $this->request->setCurrency('USD');
        $this->request->setTransactionId('ABC-123');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');
        $this->request->setDescription('DESC');

        $expected = array();
        $expected['METHOD'] = 'DoExpressCheckoutPayment';
        $expected['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $expected['PAYMENTREQUEST_0_AMT'] = '1.23';
        $expected['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
        $expected['PAYMENTREQUEST_0_INVNUM'] = 'ABC-123';
        $expected['PAYMENTREQUEST_0_DESC'] = 'DESC';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = 'SUB';
        $expected['VERSION'] = ExpressCompleteAuthorizeRequest::API_VERSION;
        $expected['TOKEN'] = 'TOKEN1234';
        $expected['PAYERID'] = 'Payer-1234';

        $this->assertEquals($expected, $this->request->getData());
    }
}
