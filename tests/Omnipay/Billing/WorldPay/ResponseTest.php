<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\WorldPay;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response('');
    }

    public function testConstructSuccess()
    {
        $response = new Response(array(
            'transStatus' => 'Y',
            'transId' => 'abc123',
            'rawAuthMessage' => 'Success Message'
        ));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getGatewayReference());
        $this->assertSame('Success Message', $response->getMessage());
    }

    public function testConstructError()
    {
        $response = new Response(array(
            'transStatus' => 'N',
            'transId' => null,
            'rawAuthMessage' => 'Declined'
        ));

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Declined', $response->getMessage());
    }
}
