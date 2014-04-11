<?php

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    public function setUp()
    {
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponse')->makePartial();
    }

    public function testConstruct()
    {
        $data = array('foo' => 'bar');
        $request = $this->getMockRequest();
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponse', array($request, $data))->makePartial();

        $this->assertSame($request, $this->response->getRequest());
        $this->assertSame($data, $this->response->getData());
    }

    public function testDefaultMethods()
    {
        $this->assertFalse($this->response->isRedirect());
        $this->assertNull($this->response->getData());
        $this->assertNull($this->response->getTransactionReference());
        $this->assertNull($this->response->getMessage());
        $this->assertNull($this->response->getCode());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage This response does not support redirection.
     */
    public function testGetRedirectResponseNotImplemented()
    {
        $this->response->getRedirectResponse();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage This response does not support redirection.
     */
    public function testGetRedirectResponseNotSupported()
    {
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponseTest_MockRedirectResponse')->makePartial();
        $this->response->shouldReceive('isRedirect')->once()->andReturn(false);

        $this->response->getRedirectResponse();
    }

    public function testGetRedirectResponseGet()
    {
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponseTest_MockRedirectResponse')->makePartial();
        $this->response->shouldReceive('getRedirectMethod')->andReturn('GET');

        $httpResponse = $this->response->getRedirectResponse();
        $this->assertSame(302, $httpResponse->getStatusCode());
        $this->assertSame('https://example.com/redirect?a=1&b=2', $httpResponse->getTargetUrl());
    }

    public function testGetRedirectResponsePost()
    {
        $data = array('foo' => 'bar', 'key&"' => '<value>');
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponseTest_MockRedirectResponse')->makePartial();
        $this->response->shouldReceive('getRedirectMethod')->andReturn('POST');
        $this->response->shouldReceive('getRedirectData')->andReturn($data);

        $httpResponse = $this->response->getRedirectResponse();
        $this->assertSame(200, $httpResponse->getStatusCode());
        $this->assertContains('<form action="https://example.com/redirect?a=1&amp;b=2" method="post">', $httpResponse->getContent());
        $this->assertContains('<input type="hidden" name="foo" value="bar" />', $httpResponse->getContent());
        $this->assertContains('<input type="hidden" name="key&amp;&quot;" value="&lt;value&gt;" />', $httpResponse->getContent());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Invalid redirect method "DELETE".
     */
    public function testGetRedirectResponseInvalidMethod()
    {
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponseTest_MockRedirectResponse')->makePartial();
        $this->response->shouldReceive('getRedirectMethod')->andReturn('DELETE');

        $this->response->getRedirectResponse();
    }
}

class AbstractResponseTest_MockRedirectResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return 'https://example.com/redirect?a=1&b=2';
    }

    public function getRedirectMethod() {}
    public function getRedirectData() {}
}
