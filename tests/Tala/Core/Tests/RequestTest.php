<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core\Tests;

use Tala\Core\Request;

/**
 * Request Test class
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = new Request();
    }

    public function testConstruct()
    {
        $this->assertEmpty($this->request->amount);
        $this->assertEmpty($this->request->currency);
    }

    public function testAmountCastsToInteger()
    {
        $this->request->amount = '6.1';
        $this->assertEquals(6, $this->request->amount);
    }

    public function testAmountDollars()
    {
        $this->request->amount = 1360;
        $this->assertSame('13.60', $this->request->amountDollars);
    }
}
