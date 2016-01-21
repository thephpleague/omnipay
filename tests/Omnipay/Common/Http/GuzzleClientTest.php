<?php

namespace Omnipay\Common\Http;

use Mockery as m;
use Omnipay\Tests\TestCase;
use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class GuzzleClientTest extends TestCase
{
    public function setUp()
    {
        $this->guzzle = m::mock(Guzzle::class)->makePartial();
        $this->client = new GuzzleClient($this->guzzle);
    }

    public function testEmptyConstruct()
    {
        $client = new GuzzleClientTest_MockGuzzleClient();
        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(Guzzle::class, $client->guzzle);
        $this->assertNotEquals($this->guzzle, $client->guzzle);
    }

    public function testGuzzleConstruct()
    {
        $client = new GuzzleClientTest_MockGuzzleClient($this->guzzle);
        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(Guzzle::class, $client->guzzle);
        $this->assertEquals($this->guzzle, $client->guzzle);
    }

    public function testSendRequest()
    {
        $request = m::mock(RequestInterface::class);
        $response = m::mock(ResponseInterface::class);

        $this->guzzle->shouldReceive('send')->once()->with($request)->andReturn($response);

        $this->assertSame($response, $this->client->sendRequest($request));
    }

    public function testCreateRequest()
    {
        $request = $this->client->createRequest('GET', 'https://thephpleague.com/', ['key' => 'value'], 'my-body');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://thephpleague.com/', $request->getUri());
        $this->assertEquals('value', $request->getHeaderLine('key'));
        $this->assertEquals('my-body', $request->getBody());
    }

    public function testCreateUri()
    {
        $uri = $this->client->createUri('https://thephpleague.com/');

        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('https://thephpleague.com/', (string) $uri);
    }

    public function testCreateStream()
    {
        $stream = $this->client->createStream('my-body');

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals('my-body', (string) $stream);
    }

    public function getGet()
    {
        $response = m::mock(ResponseInterface::class);

        $this->guzzle->shouldReceive('send')->once()->andReturn($response);

        $this->assertSame($response, $this->client->get('https://thephpleague.com/'));
    }

    public function getPost()
    {
        $response = m::mock(ResponseInterface::class);

        $this->guzzle->shouldReceive('send')->once()->andReturn($response);

        $this->assertSame($response, $this->client->post('https://thephpleague.com/', [], 'my-body'));
    }
}

class GuzzleClientTest_MockGuzzleClient extends GuzzleClient
{
    public $guzzle;
}