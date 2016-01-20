<?php

namespace Omnipay\Common\Http;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Client\Common\HttpMethodsClient;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
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
class Client extends HttpMethodsClient implements HttpClient, MessageFactory
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @param HttpClient     $httpClient     The client to send requests with.
     * @param MessageFactory $messageFactory The message factory to create requests.
     */
    public function __construct(HttpClient $httpClient = null, MessageFactory $messageFactory = null)
    {
        $this->httpClient = $httpClient ?: new GuzzleClient;
        $this->messageFactory = $messageFactory ?: new MessageFactory\DiactorosMessageFactory();

        parent::__construct($this->httpClient, $this->messageFactory);
    }

    /**
     * Creates a new PSR-7 request.
     *
     * @param string                               $method
     * @param string|UriInterface                  $uri
     * @param array                                $headers
     * @param resource|string|StreamInterface|null $body
     * @param string                               $protocolVersion
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        return $this->messageFactory->createRequest($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * Creates a new PSR-7 response.
     *
     * @param int $statusCode
     * @param string|null $reasonPhrase
     * @param array $headers
     * @param resource|string|StreamInterface|null $body
     * @param string $protocolVersion
     *
     * @return ResponseInterface
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return $this->messageFactory->createResponse($statusCode, $reasonPhrase, $headers, $body, $protocolVersion);
    }
}
