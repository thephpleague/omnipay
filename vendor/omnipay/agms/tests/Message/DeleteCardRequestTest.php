<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class DeleteCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new DeleteCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setCardReference('1032634');
    }

    public function testEndpoint()
    {
        $this->assertSame('https://gateway.agms.com/roxapi/AGMS_SAFE_API.asmx', $this->request->getEndpoint());
    }

    public function testDataWithCardReference()
    {
        $this->request->setCardReference('1032634');
        $data = $this->request->getData();

        $this->assertSame('1032634', $data['SAFE_ID']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulDeleteCard.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('SAFE record has been deactivated', $response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FailedDeleteCard.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Delete from safe failed. No SAFE ID given.', $response->getMessage());
    }
}
