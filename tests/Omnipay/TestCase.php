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

use PHPUnit_Framework_TestCase;
use ReflectionObject;
use Guzzle\Common\Event;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;
use Guzzle\Plugin\Mock\MockPlugin;
use Omnipay\Common\CreditCard;

/**
 * Base class for all Omnipay tests
 *
 * Guzzle mock methods area based on those in GuzzleTestCase
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    private $requests = array();

    /**
     * Mark a request as being mocked
     *
     * @param GuzzleRequestInterface $request
     *
     * @return self
     */
    public function addMockedRequest(GuzzleRequestInterface $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    /**
     * Get all of the mocked requests
     *
     * @return array
     */
    public function getMockedRequests()
    {
        return $this->requests;
    }

    /**
     * Get a mock response for a client by mock file name
     *
     * @param string $path Relative path to the mock response file
     *
     * @return Response
     */
    public function getMockResponse($path)
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
     * @param Client $client Client object to modify
     * @param string $paths  Path to files within the Mock folder of the service
     *
     * @return MockPlugin returns the created mock plugin
     */
    public function setMockResponse(HttpClient $client, $paths)
    {
        $this->requests = array();
        $that = $this;
        $mock = new MockPlugin(null, true);
        $client->getEventDispatcher()->removeSubscriber($mock);
        $mock->getEventDispatcher()->addListener('mock.request', function(Event $event) use ($that) {
            $that->addMockedRequest($event['request']);
        });

        foreach ((array) $paths as $path) {
            $mock->addResponse($this->getMockResponse($path));
        }

        $client->getEventDispatcher()->addSubscriber($mock);

        return $mock;
    }

    /**
     * Helper method used by gateway test classes to generate a valid test credit card
     */
    public function getValidCard()
    {
        return new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2020',
            'cvv' => '123',
        ));
    }
}
