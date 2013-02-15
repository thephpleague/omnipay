<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\AuthorizeNet;

use Omnipay\TestCase;

class ResponseTest extends TestCase
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
        $response = new Response('|1|,|1|,|1|,|This transaction has been approved.|,|JBFU0Z|,|Y|,|2176056642|,||,||,|11.00|,|CC|,|auth_only|,||,|Example|,|User|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,|D2B892FCAB855C7CAD23C42840D9D922|,|P|,|2|,||,||,||,||,||,||,||,||,||,||,|XXXX0015|,|MasterCard|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('2176056642', $response->getGatewayReference());
        $this->assertSame('This transaction has been approved.', $response->getMessage());
    }

    public function testConstructError()
    {
        $response = new Response('|3|,|1|,|6|,|The credit card number is invalid.|,||,|P|,|0|,||,||,|11.00|,|CC|,|auth_only|,||,|Example|,|User|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,|1FA778A21F0DA899BA8176E2E6E91C22|,||,||,||,||,||,||,||,||,||,||,||,||,|XXXX2222|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0', $response->getGatewayReference());
        $this->assertSame('The credit card number is invalid.', $response->getMessage());
    }
}
