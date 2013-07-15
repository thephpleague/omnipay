<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (isset($this->data->error)) {
            return (string) $this->data->error->description;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        if (isset($this->data->error)) {
            return (string) $this->data->error->code;
        }

        return null;
    }
}
