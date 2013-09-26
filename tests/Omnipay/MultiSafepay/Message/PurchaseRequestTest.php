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

use Omnipay\TestCase;
use ReflectionMethod;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'accountId' => '111111',
            'siteId' => '222222',
            'siteCode' => '333333',
            'notifyUrl' => 'http://localhost/notify',
            'cancelUrl' => 'http://localhost/cancel',
            'returnUrl' => 'http://localhost/return',
            'gateway' => 'IDEAL',
            'issuer' => 'issuer',
            'transactionId' => '123456',
            'currency' => 'EUR',
            'amount' => '100.00',
            'description' => 'desc',
            'extraData1' => 'extra 1',
            'extraData2' => 'extra 2',
            'extraData3' => 'extra 3',
            'language' => 'a language',
            'clientIp' => '127.0.0.1',
            'googleAnalyticsCode' => 'analytics code',
            'card' => array(
                'email' => 'something@example.com',
                'firstName' => 'first name',
                'lastName' => 'last name',
                'address1' => 'address 1',
                'address2' => 'address 2',
                'postcode' => '1000',
                'city' => 'a city',
                'country' => 'a country',
                'phone' => 'phone number',
            )
        ));
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://testpay.multisafepay.com/pay/?transaction=1373536347Hz4sFtg7WgMulO5q123456&lang=', $response->getRedirectUrl());
        $this->assertEquals('123456', $response->getTransactionReference());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Invalid amount', $response->getMessage());
        $this->assertEquals(1001, $response->getCode());
    }

    /**
     * @dataProvider allDataProvider
     */
    public function testGetData($xml)
    {
        $data = $this->request->getData();
        $this->assertInstanceOf('SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    /**
     * @dataProvider noIssuerDataProvider
     */
    public function testGetDataWithNonIDEALGatewayDoesNotSetIssuer($xml)
    {
        $this->request->setGateway('another');
        $data = $this->request->getData();
        $this->assertInstanceOf('SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    /**
     * @dataProvider specialCharsDataProvider
     */
    public function testGetDataWithUrlsWithSpecialChars($xml)
    {
        $this->request->setReturnUrl('http://localhost/?one=1&two=2');
        $this->request->setCancelUrl('http://localhost/?one=1&two=2');
        $this->request->setNotifyUrl('http://localhost/?one=1&two=2');
        $data = $this->request->getData();
        $this->assertInstanceOf('SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    /**
     * @covers \Omnipay\MultiSafepay\Message\PurchaseRequest::generateSignature()
     */
    public function testGenerateSignature()
    {
        $method = new ReflectionMethod('\Omnipay\MultiSafepay\Message\PurchaseRequest', 'generateSignature');
        $method->setAccessible(true);

        $signature = $method->invoke($this->request);
        $this->assertEquals('ad447bab87b8597853432c891e341db1', $signature);
    }

    public function allDataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<redirecttransaction ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
    <notification_url>http://localhost/notify</notification_url>
    <cancel_url>http://localhost/cancel</cancel_url>
    <redirect_url>http://localhost/return</redirect_url>
    <gateway>IDEAL</gateway>
  </merchant>
  <gatewayinfo>
    <issuerid>issuer</issuerid>
  </gatewayinfo>
  <customer>
    <ipaddress>127.0.0.1</ipaddress>
    <locale>a language</locale>
    <email>something@example.com</email>
    <firstname>first name</firstname>
    <lastname>last name</lastname>
    <address1>address 1</address1>
    <address2>address 2</address2>
    <zipcode>1000</zipcode>
    <city>a city</city>
    <country>a country</country>
    <phone>phone number</phone>
  </customer>
  <google_analytics>analytics code</google_analytics>
  <transaction>
    <id>123456</id>
    <currency>EUR</currency>
    <amount>10000</amount>
    <description>desc</description>
    <var1>extra 1</var1>
    <var2>extra 2</var2>
    <var3>extra 3</var3>
  </transaction>
  <signature>ad447bab87b8597853432c891e341db1</signature>
</redirecttransaction>

EOF;

        return array(
            array($xml),
        );
    }

    public function noIssuerDataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<redirecttransaction ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
    <notification_url>http://localhost/notify</notification_url>
    <cancel_url>http://localhost/cancel</cancel_url>
    <redirect_url>http://localhost/return</redirect_url>
    <gateway>another</gateway>
  </merchant>
  <customer>
    <ipaddress>127.0.0.1</ipaddress>
    <locale>a language</locale>
    <email>something@example.com</email>
    <firstname>first name</firstname>
    <lastname>last name</lastname>
    <address1>address 1</address1>
    <address2>address 2</address2>
    <zipcode>1000</zipcode>
    <city>a city</city>
    <country>a country</country>
    <phone>phone number</phone>
  </customer>
  <google_analytics>analytics code</google_analytics>
  <transaction>
    <id>123456</id>
    <currency>EUR</currency>
    <amount>10000</amount>
    <description>desc</description>
    <var1>extra 1</var1>
    <var2>extra 2</var2>
    <var3>extra 3</var3>
  </transaction>
  <signature>ad447bab87b8597853432c891e341db1</signature>
</redirecttransaction>

EOF;

        return array(
            array($xml),
        );
    }

    public function specialCharsDataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<redirecttransaction ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
    <notification_url>http://localhost/?one=1&amp;two=2</notification_url>
    <cancel_url>http://localhost/?one=1&amp;two=2</cancel_url>
    <redirect_url>http://localhost/?one=1&amp;two=2</redirect_url>
    <gateway>IDEAL</gateway>
  </merchant>
  <gatewayinfo>
    <issuerid>issuer</issuerid>
  </gatewayinfo>
  <customer>
    <ipaddress>127.0.0.1</ipaddress>
    <locale>a language</locale>
    <email>something@example.com</email>
    <firstname>first name</firstname>
    <lastname>last name</lastname>
    <address1>address 1</address1>
    <address2>address 2</address2>
    <zipcode>1000</zipcode>
    <city>a city</city>
    <country>a country</country>
    <phone>phone number</phone>
  </customer>
  <google_analytics>analytics code</google_analytics>
  <transaction>
    <id>123456</id>
    <currency>EUR</currency>
    <amount>10000</amount>
    <description>desc</description>
    <var1>extra 1</var1>
    <var2>extra 2</var2>
    <var3>extra 3</var3>
  </transaction>
  <signature>ad447bab87b8597853432c891e341db1</signature>
</redirecttransaction>

EOF;

        return array(
            array($xml),
        );
    }
}
