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

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function dataProvider()
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
