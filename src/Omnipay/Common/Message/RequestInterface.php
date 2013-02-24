<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Request Interface
 */
interface RequestInterface extends MessageInterface
{
    public function getHttpClient();

    public function setHttpClient(ClientInterface $value);

    public function getHttpRequest();

    public function setHttpRequest(HttpRequest $value);

    /**
     * Get the response to this request (if the request has been sent)
     *
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function send();
}
