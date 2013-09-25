<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

use Omnipay\TestCase;

class MrcashPurchaseRequestTest extends TestCase
{
    /**
     * @var MrcashPurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new MrcashPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/mrcash/start', $this->request->getEndpoint());
    }
}
