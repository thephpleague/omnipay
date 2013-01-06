<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PaymentExpress;

use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * DPS PaymentExpress PxPost Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        try {
            $this->data = new \SimpleXMLElement($data);
        } catch (\Exception $e) {
            throw new InvalidResponseException($e->getMessage(), $e->getCode(), $e);
        }

        if ((int) $this->data->Success == 1) {
            $this->message = (string) $this->data->HelpText;
            $this->gatewayReference = (string) $this->data->DpsTxnRef;
        } elseif (isset($this->data->HelpText)) {
            throw new Exception((string) $this->data->HelpText);
        } else {
            throw new Exception((string) $this->data->ResponseText);
        }
    }
}
