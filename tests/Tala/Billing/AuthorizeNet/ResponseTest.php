<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\AuthorizeNet;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $fixture = '|1|,|1|,|1|,|This transaction has been approved.|,|JBFU0Z|,|Y|,|2176056642|,||,||,|11.00|,|CC|,|auth_only|,||,|Example|,|User|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,|D2B892FCAB855C7CAD23C42840D9D922|,|P|,|2|,||,||,||,||,||,||,||,||,||,||,|XXXX0015|,|MasterCard|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||';
        $this->response = new Response($fixture);
    }

    public function testInvalidConstructor()
    {
        $this->setExpectedException('\Tala\Exception\InvalidResponseException');
        $response = new Response('');
    }

    public function testErrorResponse()
    {
        $this->setExpectedException('\Tala\Exception', 'The credit card number is invalid.');
        $fixture = '|3|,|1|,|6|,|The credit card number is invalid.|,||,|P|,|0|,||,||,|11.00|,|CC|,|auth_only|,||,|Example|,|User|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,|1FA778A21F0DA899BA8176E2E6E91C22|,||,||,||,||,||,||,||,||,||,||,||,||,|XXXX2222|,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||,||';
        $response = new Response($fixture);
    }

    public function testGetMessage()
    {
        $this->assertEquals('This transaction has been approved.', $this->response->getMessage());
    }

    public function testGetData()
    {
        $data = $this->response->getData();
        $this->assertEquals('auth_only', $data[11]);
    }
}
