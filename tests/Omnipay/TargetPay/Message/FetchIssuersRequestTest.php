<?php

namespace Omnipay\TargetPay\Message;

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

    public function issuersProvider()
    {
        return array(
            array(
                array(
                    '0001' => 'Sample 1',
                    '0002' => 'Sample 2',
                ),
            ),
        );
    }
}
