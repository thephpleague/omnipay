<?php

namespace Omnipay\Tpay\Message;

use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'hashType' => 'sha1',
            )
        );
    }

    public function testGetData()
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount'   => '10.00',
                'currency' => 'USD',
                'hashType' => 'sha1',
            ))->setToken('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24')
            ->setTransactionId('t59c28295aeb071b0cf6471b24f727f6456998de');

        $data = $this->request->getData();

        $this->assertSame('sale', $data['method']);
        $this->assertSame('t59c2810d59285e3e0ee9d1f1eda1c2f4c554e24', $data['cli_auth']);
        $this->assertSame('t59c28295aeb071b0cf6471b24f727f6456998de', $data['sale_auth']);

    }
}
