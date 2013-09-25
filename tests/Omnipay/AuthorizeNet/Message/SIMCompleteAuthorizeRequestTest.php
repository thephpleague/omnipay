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

class SIMCompleteAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new SIMCompleteAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetHash()
    {
        $this->assertSame(md5(''), $this->request->getHash());

        $this->request->setHashSecret('hashsec');
        $this->request->setApiLoginId('apilogin');
        $this->request->setTransactionId('trnid');
        $this->request->setAmount('10.00');

        $this->assertSame(md5('hashsecapilogintrnid10.00'), $this->request->getHash());
    }
}
