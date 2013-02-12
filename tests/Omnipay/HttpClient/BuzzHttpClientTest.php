<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\HttpClient;

use Mockery as m;

class BuzzHttpClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->browser = m::mock('\Buzz\Browser');
        $this->client = new BuzzHttpClient($this->browser);
    }

    public function testGetSetBrowser()
    {
        $newBrowser = m::mock('\Buzz\Browser');
        $this->client->setBrowser($newBrowser);

        $this->assertSame($newBrowser, $this->client->getBrowser());
    }

    public function testGet()
    {
        $headers = array('Content-type: text/plain');

        $browserResponse = m::mock('\Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('Sample Result Data');

        $this->browser->shouldReceive('get')->once()
            ->with('http://www.example.com/', $headers)
            ->andReturn($browserResponse);

        $browserResponse->shouldReceive('isSuccessful')->once()->andReturn(true);

        $response = $this->client->get('http://www.example.com/', $headers);

        $this->assertSame('Sample Result Data', $response);
    }

    public function testPostString()
    {
        $headers = array('Content-type: text/plain');
        $data = 'Sample Post Data';

        $browserResponse = m::mock('\Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('Sample Result Data');

        $this->browser->shouldReceive('post')->once()
            ->with('http://www.example.com/', $headers, $data)
            ->andReturn($browserResponse);

        $browserResponse->shouldReceive('isSuccessful')->once()->andReturn(true);

        $response = $this->client->post('http://www.example.com/', $data, $headers);

        $this->assertSame('Sample Result Data', $response);
    }

    public function testPostArray()
    {
        $headers = array('Content-type: text/plain');
        $data = array('foo' => 'bar#');

        $browserResponse = m::mock('\Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('Sample Result Data');

        $this->browser->shouldReceive('post')->once()
            ->with('http://www.example.com/', $headers, 'foo=bar%23')
            ->andReturn($browserResponse);

        $browserResponse->shouldReceive('isSuccessful')->once()->andReturn(true);

        $response = $this->client->post('http://www.example.com/', $data, $headers);

        $this->assertSame('Sample Result Data', $response);
    }
}
