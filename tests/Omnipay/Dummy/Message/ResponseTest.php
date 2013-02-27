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

class ResponseTest extends TestCase
{
    public function testSuccess()
    {
        $response = new Response(
            $this->getMockRequest(),
            array('reference' => 'abc123', 'success' => 1, 'message' => 'Success')
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testFailure()
    {
        $response = new Response(
            $this->getMockRequest(),
            array('reference' => 'abc123', 'success' => 0, 'message' => 'Failure')
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('Failure', $response->getMessage());
    }
}
