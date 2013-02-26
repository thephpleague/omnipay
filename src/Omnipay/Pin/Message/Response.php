<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Pin\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Pin Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return !isset($this->data['error']);
    }

    public function getTransactionReference()
    {
        if (isset($this->data['response']['token'])) {
            return $this->data['response']['token'];
        }
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data['response']['status_message'];
        } else {
            return $this->data['error_description'];
        }
    }
}
