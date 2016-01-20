<?php

namespace Omnipay\Common\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Http Interface
 *
 * This interface class defines the standard functions that any Omnipay http client
 * interface needs to be able to provide.
 *
 */
interface ClientInterface
{
    /**
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);

    /**
     * @param  string $method
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @return ResponseInterface
     */
    public function request($method, $uri, array $headers = [], $body = null);

    /**
     * @param  string $method
     * @param  string|UriInterface $uri
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null);

    /**
     * @param  string|UriInterface $uri
     * @return UriInterface
     */
    public function createUri($uri);

    /**
     * @param  mixed $resource
     * @return StreamInterface
     */
    public function createStream($resource);
}
