<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave;

use Omnipay\Common\AbstractResponse;

/**
 * CardSave Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getResultElement()
    {
        $resultElement = preg_replace('/Response$/', 'Result', $this->data->getName());

        return $this->data->$resultElement;
    }

    public function isSuccessful()
    {
        return 0 === (int) $this->getResultElement()->StatusCode;
    }

    public function getGatewayReference()
    {
        return (string) $this->data->TransactionOutputData['CrossReference'];
    }

    public function getMessage()
    {
        return (string) $this->getResultElement()->Message;
    }
}
