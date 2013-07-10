<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\GatewayTestCase;
use SimpleXMLElement;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccountId('111111');
        $this->gateway->setSiteId('222222');
        $this->gateway->setSiteCode('333333');

        $this->options = array(
            'transactionId' => 123456,
            'currency' => 'EUR',
            'amount' => 100.00,
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'card' => array(
                'email' => 'something@example.com',
            )
        );
    }

    /**
     * @dataProvider purchaseRequestXmlProvider
     */
    public function testPurchaseWithProvider($xml)
    {
        $request = $this->gateway->purchase($this->options);
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $request);

        $data = $request->getData();
        $this->assertInstanceOf('\SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    /**
     * @dataProvider completePurchaseRequestXmlProvider
     */
    public function testCompletePurchaseWithProvider($xml)
    {
        /** @var RequestInterface $request */
        $request = $this->gateway->completePurchase($this->options);
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $request);

        /** @var SimpleXMLElement $data */
        $data = $request->getData();
        $this->assertInstanceOf('\SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    public function purchaseRequestXmlProvider()
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

    public function completePurchaseRequestXmlProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<status ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
  </merchant>
  <transaction>
    <id>123456</id>
  </transaction>
</status>

EOF;

        return array(
            array($xml),
        );
    }
}
