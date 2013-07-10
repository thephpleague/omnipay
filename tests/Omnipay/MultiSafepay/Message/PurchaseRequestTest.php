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

class PurchaseRequestTest extends TestCase
{
    /**
     * @covers \Omnipay\MultiSafepay\Message\PurchaseRequest::getHeaders()
     */
    public function testUserAgentHeaderMustNotBeSet()
    {
        $httpClient = m::mock('\Guzzle\Http\ClientInterface');
        $httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $method = new ReflectionMethod('\Omnipay\MultiSafepay\Message\PurchaseRequest', 'getHeaders');
        $method->setAccessible(true);

        $headers = $method->invoke(new PurchaseRequest($httpClient, $httpRequest));
        $this->assertArrayHasKey('User-Agent', $headers, 'Omitting User-Agent header not allowed because then Guzzle will set it');
        $this->assertNotNull($headers['User-Agent'], 'User-Agent header must not be null because then Guzzle will set it');
        $this->assertEmpty($headers['User-Agent'], 'User-Agent header must be empty string to avoid a 403 forbidden on the gateway');
    }
}
