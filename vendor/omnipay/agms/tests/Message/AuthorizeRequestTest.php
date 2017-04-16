<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ));
    }

    public function testEndpoint()
    {
        $this->assertSame('https://gateway.agms.com/roxapi/agms.asmx', $this->request->getEndpoint());
    }
    
    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('10.00', $data['Amount']);
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

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['CCNumber']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulPurchase.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549865', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FailedPurchase.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549879', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }

}
