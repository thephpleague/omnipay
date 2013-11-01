<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'username'              => 'ernest',
                'sharedSecret'          => 'all good men die young',
                'paymentRoutingNumber'  => '840033',
                'amount'                => '10.00',
                'currency'              => 'USD',
                'card'                  => $this->getValidCard()
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
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCardRequired()
    {
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testEndpoint()
    {
        $this->assertSame('https://raven.pacnetservices.com/realtime/submit', $this->request->getEndpoint());
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
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('10000165646', $response->getTransactionReference());
        $this->assertEquals('Invalid because activity on the account is blocked.', $response->getMessage());
        $this->assertEquals('rejected:AccountBlocked', $response->getCode());
    }
}
