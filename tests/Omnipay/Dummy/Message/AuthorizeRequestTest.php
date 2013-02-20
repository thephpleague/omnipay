<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Dummy\Message;

use Omnipay\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest(array('amount' => 1000));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame(1000, $data['amount']);
    }

    public function testCreateResponse()
    {
        $response = $this->request->createResponse('12345');

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertSame('12345', $response->getGatewayReference());
    }
}
