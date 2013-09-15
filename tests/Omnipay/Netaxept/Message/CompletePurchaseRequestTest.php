<?php

namespace Omnipay\Netaxept\Message;

use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $httpRequest;

    /**
     * @var \Omnipay\Netaxept\Message\CompletePurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $this->httpRequest = $this->getHttpRequest();

        $this->request = new CompletePurchaseRequest($client, $this->httpRequest);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testGetDataThrowsExceptionWithoutResponseCode()
    {
        $this->httpRequest->query->set('transactionId', 'TRANS-123');

        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testGetDataThrowsExceptionWithoutTransactionId()
    {
        $this->httpRequest->query->set('responseCode', 'ABC-123');

        $this->request->getData();
    }
}
