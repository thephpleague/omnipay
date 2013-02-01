<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Dummy;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $response1 = new Response;
        $reference1 = $response1->getGatewayReference();
        $this->assertStringMatchesFormat('%x', $reference1);

        $response2 = new Response;
        $reference2 = $response2->getGatewayReference();
        $this->assertStringMatchesFormat('%x', $reference2);
        $this->assertNotEquals($reference1, $reference2);
    }
}
