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
            'transactionId' => 123456,
            'currency' => 'EUR',
            'amount' => 100.00,
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'card' => array(
                'email' => 'something@example.com',
            )
        ));
    }

    /**
     * @dataProvider dataProvider
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
     * @covers \Omnipay\MultiSafepay\Message\PurchaseRequest::generateSignature()
     */
    public function testGenerateSignature()
    {
        $method = new ReflectionMethod('\Omnipay\MultiSafepay\Message\PurchaseRequest', 'generateSignature');
        $method->setAccessible(true);

        $signature = $method->invoke($this->request);
        $this->assertEquals('bb886caff589f17e81b21097a39e47c2', $signature);
    }

    public function dataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<redirecttransaction ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
  </merchant>
  <customer>
    <ipaddress>127.0.0.1</ipaddress>
    <email>something@example.com</email>
  </customer>
  <transaction>
    <id>123456</id>
    <currency>EUR</currency>
    <amount>10000</amount>
    <description>desc</description>
  </transaction>
  <signature>bb886caff589f17e81b21097a39e47c2</signature>
</redirecttransaction>

EOF;

        return array(
            array($xml),
        );
    }
}
