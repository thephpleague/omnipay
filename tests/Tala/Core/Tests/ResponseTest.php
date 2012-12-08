<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core\Tests;

use Tala\Core\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyConstructor()
    {
        $this->response = new Response();
        $this->assertFalse($this->response->isRedirect());
        $this->assertNull($this->response->getData());
        $this->assertNull($this->response->getGatewayReference());
        $this->assertNull($this->response->getMessage());
    }

    public function testConstructorWithGatewayReference()
    {
        $this->response = new Response('12345');
        $this->assertEquals('12345', $this->response->getGatewayReference());
    }

    public function testConstructorWithMessage()
    {
        $this->response = new Response(null, 'Test Message');
        $this->assertEquals('Test Message', $this->response->getMessage());
    }
}
