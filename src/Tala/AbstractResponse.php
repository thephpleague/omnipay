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

/**
 * Base Response class
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected $data;

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
