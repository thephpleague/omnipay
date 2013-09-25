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

class IdealPurchaseRequestTest extends TestCase
{
    /**
     * @var IdealPurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new IdealPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.targetpay.com/ideal/start', $this->request->getEndpoint());
    }
}
