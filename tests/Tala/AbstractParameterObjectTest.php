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

class AbstractParameterObjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\Tala\AbstractParameterObject');
    }

    public function getMockSubclass()
    {
        $mock = $this->getMock('\Tala\AbstractParameterObject', array('getValidParameters'));
        $mock->expects($this->atLeastOnce())
            ->method('getValidParameters')
            ->will($this->returnValue(array('firstValid', 'secondValid')));

        return $mock;
    }

    public function testParameterDefault()
    {
        $this->assertNull($this->object->someParameter);
    }

    public function testParameterProperty()
    {
        $this->object->someParameter = 'hello';
        $this->assertEquals('hello', $this->object->someParameter);
    }

    public function testDynamicGetter()
    {
        $this->object->someParameter = 'example1';
        $this->assertEquals('example1', $this->object->getSomeParameter());
    }

    public function testDynamicSetter()
    {
        $this->object->setSomeParameter('example2');
        $this->assertEquals('example2', $this->object->someParameter);
    }

    public function testDynamicMissingMethod()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->object->doesntExist();
    }

    public function testGetValidParameters()
    {
        $this->assertNull($this->object->getValidParameters());
    }

    public function testIsValidParameter()
    {
        $this->assertTrue($this->object->isValidParameter('someParameter'));
    }

    public function testIsValidParameterBlankString()
    {
        $this->assertFalse($this->object->isValidParameter(''));
    }

    public function testOverrideValidParameters()
    {
        $mock = $this->getMockSubclass();
        $this->assertTrue($mock->isValidParameter('firstValid'));
        $this->assertTrue($mock->isValidParameter('secondValid'));
        $this->assertFalse($mock->isValidParameter('someParameter'));
    }

    public function testOverrideSetValidParameter()
    {
        $mock = $this->getMockSubclass();
        $mock->firstValid = 'something';
        $this->assertEquals('something', $mock->firstValid);
    }

    public function testOverrideGetInvalidParameter()
    {
        $mock = $this->getMockSubclass();
        $this->setExpectedException('BadMethodCallException');
        $result = $mock->someParameter;
    }

    public function testOverrideSetInvalidParameter()
    {
        $mock = $this->getMockSubclass();
        $this->setExpectedException('BadMethodCallException');
        $mock->someParameter = 'hello';
    }
}
