<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payflow;

use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * Payflow Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new InvalidResponseException;
        }

        parse_str($data, $this->data);

        if ($this->data['RESULT'] > 0) {
            throw new Exception($this->data['RESPMSG']);
        }

        $this->message = $this->data['RESPMSG'];
        $this->gatewayReference = $this->data['PNREF'];
    }
}
