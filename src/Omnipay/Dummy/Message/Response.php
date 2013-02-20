<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Dummy\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Dummy Response
 */
class Response extends AbstractResponse
{
    protected $gatewayReference;

    public function __construct($gatewayReference)
    {
        if (empty($gatewayReference)) {
            throw new InvalidResponseException;
        }

        $this->gatewayReference = $gatewayReference;
    }

    public function isSuccessful()
    {
        return true;
    }

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }
}
