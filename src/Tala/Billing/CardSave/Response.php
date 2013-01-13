<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\CardSave;

/**
 * CardSave Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        $this->data = $data;
        $this->gatewayReference = (string) $data->TransactionOutputData['CrossReference'];
    }
}
