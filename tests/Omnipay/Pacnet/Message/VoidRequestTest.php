<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\TestCase;

class VoidRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'username'              => 'ernest',
                'sharedSecret'          => 'all good men die young',
                'paymentRoutingNumber'  => '840033',
                'transactionReference'  => '10000165919'
            )
        );
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The username parameter is required
     */
    public function testUsernameRequired()
    {
        $this->request->setUsername(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The sharedSecret parameter is required
     */
    public function testSharedSecretRequired()
    {
        $this->request->setSharedSecret(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The paymentRoutingNumber parameter is required
     */
    public function testPaymentRoutingNumberRequired()
    {
        $this->request->setPaymentRoutingNumber(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The transactionReference parameter is required
     */
    public function testTransactionReferenceRequired()
    {
        $this->request->setTransactionReference(null);
        $this->request->getData();
    }

    public function testEndpoint()
    {
        $this->assertSame('https://raven.pacnetservices.com/realtime/void', $this->request->getEndpoint());
    }

    public function testTransactionReference()
    {
        $data = $this->request->getData();
        $this->assertSame('10000165919', $data['TrackingNumber']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }
}
