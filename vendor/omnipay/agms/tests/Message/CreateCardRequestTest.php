<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class CreateCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setCard($this->getValidCard());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://gateway.agms.com/roxapi/AGMS_SAFE_API.asmx', $this->request->getEndpoint());
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
        $this->setMockHttpResponse('SuccessfulCreateCard.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1032628', $response->getCardReference());
        $this->assertSame('SAFE Record added successfully. No transaction processed.', $response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FailedCreateCard.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Add to safe failed: Missing \'credit card\' info.', $response->getMessage());
    }
}
