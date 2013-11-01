<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\TestCase;

class RefundRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'username'              => 'ernest',
                'sharedSecret'          => 'all good men die young',
                'paymentRoutingNumber'  => '840033',
                'amount'                => '10.00',
                'currency'              => 'USD',
                'transactionReference'  => '10000165604'
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
     * @expectedExceptionMessage The amount parameter is required
     */
    public function testAmountRequired()
    {
        $this->request->setAmount(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The currency parameter is required
     */
    public function testCurrencyRequired()
    {
        $this->request->setCurrency(null);
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
        $this->assertSame('https://raven.pacnetservices.com/realtime/submit', $this->request->getEndpoint());
    }

    public function testTransactionReference()
    {
        $data = $this->request->getData();
        $this->assertSame('10000165604', $data['TemplateNumber']);
    }

    public function testAmount()
    {
        $data = $this->request->getData();
        $this->assertSame(1000, $data['Amount']);
    }

    public function testCurrency()
    {
        $data = $this->request->getData();
        $this->assertSame('USD', $data['CurrencyCode']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertEquals('Invalid because the original payment is not a settled debit.', $response->getMessage());
        $this->assertEquals('invalid:OriginalPaymentNotSettled', $response->getCode());
    }
}
