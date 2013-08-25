<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testDefaultMethods()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse[isSuccessful]');

        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getData());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testGetRequest()
    {
        $request = m::mock('\Omnipay\Common\Message\RequestInterface');
        $response = new GetRedirectResponse($request, array());

        $this->assertSame($request, $response->getRequest());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     */
    public function testCannotRedirectResponseThatIsNotRedirectResponseInterface()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse[isSuccessful,isRedirect]');

        $response->getRedirectResponse();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     */
    public function testCannotRedirectResponseThatIsImproperlyConfigured()
    {
        $request = m::mock('\Omnipay\Common\Message\RequestInterface');
        $response = new ImproperlyConfiguredRedirectResponse($request, array());

        $response->getRedirectResponse();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     */
    public function testCannotRedirectResponseWithUnsupportedMethod()
    {
        $request = m::mock('\Omnipay\Common\Message\RequestInterface');
        $response = new BadRedirectMethodRedirectResponse($request, array());

        $response->getRedirectResponse();
    }

    public function testGetRedirectResponse()
    {
        $request = m::mock('\Omnipay\Common\Message\RequestInterface');
        $data = array();
        $response = new GetRedirectResponse($request, $data);

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response->getRedirectResponse());
    }

    public function testPostRedirectResponse()
    {
        $request = m::mock('\Omnipay\Common\Message\RequestInterface');
        $data = array();
        $response = new PostRedirectResponse($request, $data);

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response->getRedirectResponse());
    }
}
