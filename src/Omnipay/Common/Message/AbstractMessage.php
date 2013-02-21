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
 * Abstract Message
 */
abstract class AbstractMessage implements MessageInterface
{
    protected $gateway;

    public function getGateway()
    {
        return $this->gateway;
    }

    public function setGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }
}
