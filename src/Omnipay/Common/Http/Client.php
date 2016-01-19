<?php

namespace Omnipay\Common\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param  $method
     * @param  $uri
     * @param  array $headers
     * @param  null $body
     * @return ResponseInterface
     */
    public function request($method, $uri, array $headers = [], $body = null)
    {
        $request = $this->createRequest($method, $uri, $headers, $body);

        return $this->send($request);
    }

    /**
     * @param  string $method
     * @param  string $uri
     * @param  array $headers
     * @param  null $body
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null)
    {
        return new Request($method, $uri, $headers, $body);
    }
}