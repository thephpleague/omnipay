<?php

namespace Omnipay\Common\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Default Http Client
 *
 * Implementation of the Http ClientInterface by using Guzzle.
 *
 */
class GuzzleClient implements ClientInterface
{
    /** @var  \GuzzleHttp\Client */
    public $guzzle;

    public function __construct(Client $client = null)
    {
        $this->guzzle = $client ?: new Client();
    }

    /**
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request)
    {
        return $this->guzzle->send($request);
    }

    /**
     * @param  string $method
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @param  string $protocolVersion
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return new Request($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * @param  string|UriInterface $uri
     * @return UriInterface
     */
    public function createUri($uri)
    {
        return \GuzzleHttp\Psr7\uri_for($uri);
    }

    /**
     * @param  mixed $resource
     * @return StreamInterface
     */
    public function createStream($resource)
    {
        return \GuzzleHttp\Psr7\stream_for($resource);
    }

    /**
     * Send a GET request
     *
     * @param string|UriInterface $uri
     * @param array $headers
     * @return ResponseInterface
     */
    public function get($uri, array $headers = [])
    {
        $request = $this->createRequest('GET', $uri, $headers);

        return $this->sendRequest($request);
    }

    /**
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @return ResponseInterface
     */
    public function post($uri, array $headers = [], $body = null)
    {
        $request = $this->createRequest('GET', $uri, $headers, $body);

        return $this->sendRequest($request);
    }
}
