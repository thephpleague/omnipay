<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\TestCase;

class SIMCompleteAuthorizeResponseTest extends TestCase
{
    public function testSuccess()
    {
        $response = new SIMCompleteAuthorizeResponse($this->getMockRequest(), array('x_response_code' => '1', 'x_trans_id' => '12345'));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testFailure()
    {
        $response = new SIMCompleteAuthorizeResponse($this->getMockRequest(), array('x_response_code' => '0', 'x_response_reason_text' => 'Declined'));

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }
}
