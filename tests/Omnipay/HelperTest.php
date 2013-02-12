<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

use Mockery as m;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testCamelCase()
    {
        $result = Helper::camelCase('test_case');
        $this->assertEquals('testCase', $result);
    }

    public function testCamelCaseAlreadyCorrect()
    {
        $result = Helper::camelCase('testCase');
        $this->assertEquals('testCase', $result);
    }

    public function testValidateLuhnValid()
    {
        $result = Helper::validateLuhn('4111111111111111');
        $this->assertTrue($result);
    }

    public function testValidateLuhnInvalid()
    {
        $result = Helper::validateLuhn('4111111111111110');
        $this->assertFalse($result);
    }

    public function testValidateLuhnNull()
    {
        $result = Helper::validateLuhn(null);
        $this->assertTrue($result);
    }

    public function testInitializeIgnoresNull()
    {
        $target = m::mock();
        Helper::initialize($target, null);
    }

    public function testInitializeIgnoresString()
    {
        $target = m::mock();
        Helper::initialize($target, 'invalid');
    }

    public function testInitializeCallsSetters()
    {
        $target = m::mock('\Omnipay\CreditCard');
        $target->shouldReceive('setName')->once()->with('adrian');
        $target->shouldReceive('setNumber')->once()->with('1234');

        Helper::initialize($target, array('name' => 'adrian', 'number' => '1234'));
    }

    public function testInitializeIgnoresInvalidParameters()
    {
        $target = m::mock('\Omnipay\CreditCard');
        $target->shouldReceive('setName')->once()->with('adrian');

        Helper::initialize($target, array('name' => 'adrian', 'extra' => 'invalid'));
    }
}
