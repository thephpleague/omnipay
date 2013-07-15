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

class FetchIssuersRequestTest extends TestCase
{
    /**
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'accountId' => '111111',
            'siteId' => '222222',
            'siteCode' => '333333',
        ));
    }

    /**
     * @dataProvider issuersProvider
     */
    public function testSendSuccess($expected)
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');

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

    public function issuersProvider()
    {
        return array(
            array(
                array(
                    '0031' => 'ABN AMRO',
                    '0751' => 'SNS Bank',
                    '0721' => 'ING',
                    '0021' => 'Rabobank',
                    '0091' => 'Friesland Bank',
                    '0761' => 'ASN Bank',
                    '0771' => 'SNS Regio Bank',
                    '0511' => 'Triodos Bank',
                    '0161' => 'Van Lanschot Bankiers',
                    '0801' => 'Knab',
                ),
            ),
        );
    }

    public function dataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<idealissuers ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
  </merchant>
</idealissuers>

EOF;

        return array(
            array($xml),
        );
    }
}
