<?php

namespace League\Omnipay;

use Mockery as m;
use League\Omnipay\Common\Http\ClientInterface;
use League\Omnipay\Common\Http\GuzzleClient;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionObject;
use GuzzleHttp\Handler\MockHandler;
use Zend\Diactoros\ServerRequest;

/**
 * Guzzle mock methods area based on those in GuzzleTestCase
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    private $mockHttpRequests = array();
    private $mockRequest;
    private $httpClient;
    private $httpRequest;

    /**
     * Converts a string to camel case
     *
     * @param string $str
     * @return string
     */
    public function camelCase($str)
    {
        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * Mark a request as being mocked
     *
     * @param RequestInterface $request
     *
     * @return self
     */
    public function addMockedHttpRequest(RequestInterface $request)
    {
        $this->mockHttpRequests[] = $request;

        return $this;
    }

    /**
     * Get all of the mocked requests
     *
     * @return array
     */
    public function getMockedRequests()
    {
        return $this->mockHttpRequests;
    }

    /**
     * Get a mock response for a client by mock file name
     *
     * @param string $path Relative path to the mock response file
     *
     * @return ResponseInterface
     */
    public function getMockHttpResponse($path)
    {
        if ($path instanceof ResponseInterface) {
            return $path;
        }

        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());

        // if mock file doesn't exist, check parent directory
        if (!file_exists($dir.'/Mock/'.$path) && file_exists($dir.'/../Mock/'.$path)) {
            return \GuzzleHttp\Psr7\parse_response($dir.'/../Mock/'.$path);
        }

        return \GuzzleHttp\Psr7\parse_response($dir.'/Mock/'.$path);
    }

    /**
     * Set a mock response from a mock file on the next client request.
     *
     * This method assumes that mock response files are located under the
     * Mock/ subdirectory of the current class. A mock response is added to the next
     * request sent by the client.
     *
     * An array of path can be provided and the next x number of client requests are
     * mocked in the order of the array where x = the array length.
     *
     * @param array|string $paths Path to files within the Mock folder of the service
     *
     * @return MockHandler returns the created mock plugin
     */
    public function setMockHttpResponse($paths)
    {
        $this->mockHttpRequests = array();

        $queue = [];
        foreach ((array) $paths as $path) {
            $queue[] = $this->getMockHttpResponse($path);
        }

        $mockHandler = new MockHandler($queue);

        $guzzle = new \GuzzleHttp\Client(['handler' => $mockHandler]);
        $this->httpClient = new GuzzleClient($guzzle);

        return $mockHandler;
    }

    /**
     * Helper method used by gateway test classes to generate a valid test credit card
     */
    public function getValidCard()
    {
        return array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => rand(1, 12),
            'expiryYear' => gmdate('Y') + rand(1, 5),
            'cvv' => rand(100, 999),
            'billingAddress1' => '123 Billing St',
            'billingAddress2' => 'Billsville',
            'billingCity' => 'Billstown',
            'billingPostcode' => '12345',
            'billingState' => 'CA',
            'billingCountry' => 'US',
            'billingPhone' => '(555) 123-4567',
            'shippingAddress1' => '123 Shipping St',
            'shippingAddress2' => 'Shipsville',
            'shippingCity' => 'Shipstown',
            'shippingPostcode' => '54321',
            'shippingState' => 'NY',
            'shippingCountry' => 'US',
            'shippingPhone' => '(555) 987-6543',
        );
    }

    public function getMockRequest()
    {
        if (null === $this->mockRequest) {
            $this->mockRequest = m::mock('\League\Omnipay\Common\Message\RequestInterface');
        }

        return $this->mockRequest;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new GuzzleClient(new \GuzzleHttp\Client);
        }

        return $this->httpClient;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getHttpRequest()
    {
        if (null === $this->httpRequest) {
            $this->httpRequest = new ServerRequest();
        }

        return $this->httpRequest;
    }
}