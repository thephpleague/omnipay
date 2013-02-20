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

use Omnipay\Common\GatewayInterface;

/**
 * Request Interface
 */
interface RequestInterface
{
    public function getGateway();

    public function setGateway(GatewayInterface $gateway);

    public function getResponse();

    public function setResponse(ResponseInterface $response);

    public function createResponse($data);

    public function send();
}
