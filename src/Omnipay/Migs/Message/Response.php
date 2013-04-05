<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;


/**
 * Migs Purchase Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        if(is_array($data))
        {
            $this->data = $data;
        }
        else
        {
            parse_str($data, $this->data);
        }
    }

    public function isSuccessful()
    {
        if (isset($this->data['vpc_TxnResponseCode'])) {
            
            $responseCode = $this->data['vpc_TxnResponseCode'];

            if ($responseCode == "0") {
                return true;
            }
        }

        return false;
    }

    public function getTransactionReference()
    {
        return isset($this->data['vpc_ReceiptNo']) ? $this->data['vpc_ReceiptNo'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['vpc_Message']) ? $this->data['vpc_Message'] : null;
    }
}
