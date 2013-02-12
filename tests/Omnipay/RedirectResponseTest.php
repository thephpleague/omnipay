<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

use Mockery as m;

class RedirectResponseTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = new RedirectResponse('https://www.example.com/redirect');
    }

    public function testIsSuccessful()
    {
        $this->assertFalse($this->response->isSuccessful());
    }

    public function testIsRedirect()
    {
        $this->assertTrue($this->response->isRedirect());
    }

    public function testGetRedirectUrl()
    {
        $this->assertSame('https://www.example.com/redirect', $this->response->getRedirectUrl());
    }

    public function testGetGatewayReference()
    {
        $this->assertNull($this->response->getGatewayReference());
    }

    public function testGetMessage()
    {
        $this->assertNull($this->response->getMessage());
    }
}
