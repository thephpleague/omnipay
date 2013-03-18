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
 * Migs Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {

        if (isset($this->data['vpc_TxnResponseCode'])) {

            if ($this->data['vpc_TxnResponseCode'] == "0") {
                return true;
            }

            // if(isset($this->data['vpc_SecureHash']))
            // {
            //     $secure_secret = $this->request->getSecureHash();

            //     if($this->data['vpc_SecureHash'] == $this->calculateHash($this->data, $secure_secret))
            //     {
            //         return true;
            //     }
            // }
        }

        return false;
    }

    public function getTransactionReference()
    {
        return (isset($this->data['vpc_ReceiptNo']) ? $this->data['vpc_ReceiptNo'] : null);
    }

    public function getMessage()
    {
        return (isset($this->data['vpc_Message']) ? $this->data['vpc_Message'] : null);
    }

    private function calculateHash($data, $secure_secret)
    {
        $md5HashData = $secure_secret;

        foreach ($this->data as $k => $v) {
            if ($k !== "vpc_SecureHash") {
                $md5HashData .= $v;
            }
        }

        $hash = strtoupper(md5($md5HashData));

        return $hash;
    }
}
