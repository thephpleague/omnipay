<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Mockery as m;

class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    private $httpClient;
    private $httpRequest;

    /**
     * @var AbstractGateway
     */
    private $gateway;

    protected function setUp()
    {
        $this->httpClient = m::mock('Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('Symfony\Component\HttpFoundation\Request');

        $this->gateway = $this->getMockForAbstractClass('Tala\AbstractGateway', array($this->httpClient, $this->httpRequest));
        // TODO: figure out how to do this in Mockery - the below doesn't work
        //$this->gateway = m::mock('Tala\AbstractGateway', array($this->httpClient, $this->httpRequest));
    }

    public function testGetDefaultSettings()
    {
        $this->assertEquals(array(), $this->gateway->getDefaultSettings());
    }

    public function testHttpClient()
    {
        $this->assertInstanceOf('Tala\HttpClient\HttpClientInterface', $this->gateway->getHttpClient());
        $this->assertEquals($this->httpClient, $this->gateway->getHttpClient());
    }

    public function testHttpRequest()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $this->gateway->getHttpRequest());
        $this->assertEquals($this->httpRequest, $this->gateway->getHttpRequest());
    }
}
