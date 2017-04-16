<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class UpdateCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new UpdateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setCard($this->getValidCard());
        $this->request->setCardReference('1032634');
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

    public function testDataWithCardReference()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $this->request->setCardReference('1032634');
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['CCNumber']);
        $this->assertSame('1032634', $data['SAFE_ID']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulUpdateCard.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1032634', $response->getCardReference());
        $this->assertSame('SAFE Record updated successfully. No transaction processed.', $response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FailedUpdateCard.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Update safe failed. No SAFE ID given.', $response->getMessage());
    }
}
