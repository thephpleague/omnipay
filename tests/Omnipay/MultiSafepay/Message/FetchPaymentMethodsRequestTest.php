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

class FetchPaymentMethodsRequestTest extends TestCase
{
    /**
     * @var FetchPaymentMethodsRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'accountId' => '111111',
            'siteId' => '222222',
            'siteCode' => '333333',
            'country' => 'NL',
        ));
    }

    /**
     * @dataProvider paymentMethodsProvider
     */
    public function testSendSuccess($expected)
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $response->getPaymentMethods());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Invalid merchant security code', $response->getMessage());
        $this->assertEquals(1005, $response->getCode());
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

    public function paymentMethodsProvider()
    {
        return array(
            array(
                array(
                    'VISA' => 'Visa CreditCards',
                    'WALLET' => 'MultiSafepay',
                    'IDEAL' => 'iDEAL',
                    'BANKTRANS' => 'Bank Transfer',
                    'MASTERCARD' => 'MasterCard',
                ),
            ),
        );
    }

    public function dataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<gateways ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
  </merchant>
  <customer>
    <country>NL</country>
  </customer>
</gateways>

EOF;

        return array(
            array($xml),
        );
    }
}
