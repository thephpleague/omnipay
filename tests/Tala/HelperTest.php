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
}
