<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

/**
 * Base Response class
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract public function isSuccessful();

    public function isRedirect()
    {
        return false;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return null;
    }

    public function getGatewayReference()
    {
        return null;
    }
}
