<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testDefaultMethods()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse[isSuccessful]');

        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getData());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     */
    public function testCannotRedirectResponseThatIsNotRedirectResponseInterface()
    {
        $response = m::mock('\Omnipay\Common\Message\AbstractResponse[isSuccessful,isRedirect]');

        $response->getRedirectResponse();
    }
}
