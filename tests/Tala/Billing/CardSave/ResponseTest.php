<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\CardSave;

use SimpleXmlElement;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $response = new Response(new SimpleXmlElement('<Response><TransactionOutputData CrossReference="abc123" /></Response>'));

        $this->assertEquals('abc123', $response->getGatewayReference());
    }
}
