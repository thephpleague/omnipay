<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay\Message;

use Mockery as m;
use Omnipay\TestCase;
use ReflectionMethod;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = m::mock('\Omnipay\MultiSafepay\Message\AbstractRequest[getData,send]');
    }

    /**
     * @covers \Omnipay\MultiSafepay\Message\AbstractRequest::getHeaders()
     */
    public function testUserAgentHeaderMustNotBeSet()
    {
        $method = new ReflectionMethod('\Omnipay\MultiSafepay\Message\AbstractRequest', 'getHeaders');
        $method->setAccessible(true);

        $headers = $method->invoke($this->request);
        $this->assertArrayHasKey('User-Agent', $headers, 'Omitting User-Agent header not allowed because then Guzzle will set it and cause 403 Forbidden on the gateway');
        $this->assertEquals('Omnipay', $headers['User-Agent'], 'User-Agent header set');
    }
}
