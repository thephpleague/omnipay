<?php

namespace Omnipay\Common\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param  $method
     * @param  $uri
     * @param  array $headers
     * @param  null $body
     * @return ResponseInterface
     */
    public function request($method, $uri, array $headers = [], $body = null);

    /**
     * @param  string $method
     * @param  string $uri
     * @param  array $headers
     * @param  null $body
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null);
}
