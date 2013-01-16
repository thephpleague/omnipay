<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Tala\AbstractParameterObject;
use Tala\HttpClient\HttpClientInterface;

/**
 * Base payment gateway class
 *
 * @author Adrian Macneil <adrian@adrianmacneil.com>
 * @author Alexander Deruwe <alexander.deruwe@gmail.com>
 */
abstract class AbstractGateway extends AbstractParameterObject implements GatewayInterface
{
    private $httpClient;
    private $httpRequest;

    /**
     * @param HttpClientInterface $httpClient  Tala HTTP Client
     * @param HttpRequest         $httpRequest Symfony2 HTTP Request
     * @param array               $parameters  Additional gateway specific parameters
     */
    public function __construct(HttpClientInterface $httpClient, HttpRequest $httpRequest, $parameters = array())
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;

        parent::__construct($parameters);
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function getValidParameters()
    {
        return array_merge(array('httpClient', 'httpRequest'), array_keys($this->getDefaultSettings()));
    }
}
