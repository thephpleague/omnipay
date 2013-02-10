<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Mockery as m;

class AbstractResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultMethods()
    {
        $response = m::mock('\Tala\AbstractResponse[isSuccessful]');

        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getData());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }
}
