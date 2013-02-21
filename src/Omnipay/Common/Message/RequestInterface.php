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

/**
 * Request Interface
 */
interface RequestInterface extends MessageInterface
{
    public function getResponse();

    public function setResponse(ResponseInterface $response);

    public function createResponse($data);

    public function send();
}
