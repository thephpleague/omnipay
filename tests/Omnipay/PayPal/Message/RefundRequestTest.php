<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Message\RefundRequest;
use Omnipay\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     * @var \Omnipay\PayPal\Message\RefundRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();

        $request = $this->getHttpRequest();

        $this->request = new RefundRequest($client, $request);
    }

    /**
     * @dataProvider provideRefundTypes
     */
    public function testGetData($type, $amount)
    {
        $this->request->setAmount($amount);
        $this->request->setCurrency('USD');
        $this->request->setTransactionReference('ABC-123');
        $this->request->setUsername('testuser');
        $this->request->setPassword('testpass');
        $this->request->setSignature('SIG');
        $this->request->setSubject('SUB');

        $expected = array();
        $expected['REFUNDTYPE'] = $type;
        $expected['METHOD'] = 'RefundTransaction';
        $expected['TRANSACTIONID'] = 'ABC-123';
        $expected['USER'] = 'testuser';
        $expected['PWD'] = 'testpass';
        $expected['SIGNATURE'] = 'SIG';
        $expected['SUBJECT'] = 'SUB';
        $expected['VERSION'] = RefundRequest::API_VERSION;
        if ($amount) {
            $expected['AMT'] = $amount;
            $expected['CURRENCYCODE'] = 'USD';
        }

        $this->assertEquals($expected, $this->request->getData());
    }

    public function provideRefundTypes()
    {
        return array(
            'Partial' => array('Partial', '1.23'),
            'Full' => array('Full', '0'),
        );
    }
}
