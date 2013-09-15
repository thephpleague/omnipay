<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave\Message;

use Mockery as m;
use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testPurchaseWithoutStatusCode()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailureWithoutStatusCode.txt');
        new Response($this->getMockRequest(), $httpResponse->getBody());
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('130215141054377801316798', $response->getTransactionReference());
        $this->assertSame('AuthCode: 672167', $response->getMessage());
        $this->assertEmpty($response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getTransactionReference());
        $this->assertSame('Input variable errors', $response->getMessage());
    }

    public function testRedirect()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseRedirect.txt');

        $request = m::mock('\Omnipay\Common\Message\AbstractRequest');
        $request->shouldReceive('getReturnUrl')->once()->andReturn('http://store.example.com/');

        $response = new Response($request, $httpResponse->getBody());

        $this->assertTrue($response->isRedirect());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame('http://some.redirect.com/', $response->getRedirectUrl());

        $expectedData = array(
            'PaReq' => 'Some PaREQ',
            'TermUrl' => 'http://store.example.com/',
            'MD' => '130215141054377801316798',
        );
        $this->assertEquals($expectedData, $response->getRedirectData());
    }
}
