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
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Migs\TwoPartyGateway;

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

    // public function isSuccessful()
    // {
    //     if (isset($this->data['vpc_TxnResponseCode'])) {
            
    //         $responseCode = $this->data['vpc_TxnResponseCode'];

    //         if(isset($this->data['vpc_SecureHash'])) {
    //             $secureHash = $this->data['vpc_SecureHash'];
    //             $calculatedHash = $this->calculateHash($this->data);

    //             if($secureHash == $calculatedHash) {
    //                 if ($responseCode == "0") {
    //                     return true;
    //                 }
    //             }
    //         }
    //     }

    //     return false;
    // }

    public function getTransactionReference()
    {
        return isset($this->data['vpc_ReceiptNo']) ? $this->data['vpc_ReceiptNo'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['vpc_Message']) ? $this->data['vpc_Message'] : null;
    }

    private function calculateHash($data)
    {
        $secureSecret = $this->request->getSecureHash();

        $hash = $secureSecret;

        ksort($data);

        foreach ($this->data as $k => $v) {
            if (substr($k, 0, 4) === 'vpc_' && $k !== 'vpc_SecureHash') {
                $hash .= $v;
            }
        }

        $hash = strtoupper(md5($hash));

        return $hash;
    }
}
