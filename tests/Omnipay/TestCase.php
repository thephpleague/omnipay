<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use ReflectionObject;
use Guzzle\Common\Event;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base class for all Omnipay tests
 *
 * Guzzle mock methods area based on those in GuzzleTestCase
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    private $mockHttpRequests = array();
    private $mockRequest;
    private $httpClient;
    private $httpRequest;

    /**
     * Mark a request as being mocked
     *
     * @param GuzzleRequestInterface $request
     *
     * @return self
     */
    public function addMockedHttpRequest(GuzzleRequestInterface $request)
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
     * @return Response
     */
    public function getMockHttpResponse($path)
    {
        if ($path instanceof Response) {
            return $path;
        }

        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());

        // if mock file doesn't exist, check parent directory
        if (!file_exists($dir.'/Mock/'.$path) && file_exists($dir.'/../Mock/'.$path)) {
            return MockPlugin::getMockFile($dir.'/../Mock/'.$path);
        }

        return MockPlugin::getMockFile($dir.'/Mock/'.$path);
    }

    /**
     * Set a mock response from a mock file on the next client request.
     *
     * This method assumes that mock response files are located under the
     * Mock/ subdirectory of the current class. A mock response is added to the next
     * request sent by the client.
     *
     * @param string $paths Path to files within the Mock folder of the service
     *
     * @return MockPlugin returns the created mock plugin
     */
    public function setMockHttpResponse($paths)
    {
        $this->mockHttpRequests = array();
        $that = $this;
        $mock = new MockPlugin(null, true);
        $this->getHttpClient()->getEventDispatcher()->removeSubscriber($mock);
        $mock->getEventDispatcher()->addListener('mock.request', function(Event $event) use ($that) {
            $that->addMockedHttpRequest($event['request']);
        });

        foreach ((array) $paths as $path) {
            $mock->addResponse($this->getMockHttpResponse($path));
        }

        $this->getHttpClient()->getEventDispatcher()->addSubscriber($mock);

        return $mock;
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
            'expiryYear' => date('Y') + rand(1, 5),
            'cvv' => rand(100, 999),
        );
    }

    public function getMockRequest()
    {
        if (null === $this->mockRequest) {
            $this->mockRequest = m::mock('\Omnipay\Common\Message\RequestInterface');
        }

        return $this->mockRequest;
    }

    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient;
        }

        return $this->httpClient;
    }

    public function getHttpRequest()
    {
        if (null === $this->httpRequest) {
            $this->httpRequest = new HttpRequest;
        }

        return $this->httpRequest;
    }
}
