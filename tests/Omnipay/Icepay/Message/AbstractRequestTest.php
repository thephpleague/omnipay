<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new AbstractRequestStub($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testTimestampIsNeverNull()
    {
        $timestamp = $this->request->getTimestamp();

        $this->assertNotNull($timestamp, 'timestamp is never null');
        sleep(1);
        $this->assertEquals($timestamp, $this->request->getTimestamp(), 'generated timestamp is stored');
    }
}

class AbstractRequestStub extends AbstractRequest
{
    public function getData()
    {
    }

    public function send()
    {
    }

    protected function generateSignature()
    {
    }
}
