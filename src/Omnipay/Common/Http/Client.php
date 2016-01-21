<?php

namespace Omnipay\Common\Http;

use GuzzleHttp\Client as GuzzleClient;
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
class Client implements ClientInterface
{
    /** @var  \GuzzleHttp\Client */
    public $guzzle;

    public function __construct(GuzzleClient $client = null)
    {
        $this->guzzle = $client ?: new GuzzleClient();
    }

    /**
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        return $this->guzzle->send($request);
    }

    /**
     * @param  string $method
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @return ResponseInterface
     */
    public function request($method, $uri, array $headers = [], $body = null)
    {
        $request = $this->createRequest($method, $uri, $headers, $body);

        return $this->send($request);
    }

    /**
     * @param  string $method
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null)
    {
        return new Request($method, $uri, $headers, $body);
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
        \GuzzleHttp\Psr7\stream_for($resource);
    }
}
