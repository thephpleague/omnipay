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

/**
 * Migs Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'returnUrl', 'transactionId');

        $data = $this->getBaseData();

        $data['vpc_Amount']      = $this->getAmount();
        $data['vpc_MerchTxnRef'] = $this->generateNonce(40);
        $data['vpc_OrderInfo']   = $this->generateNonce(34);
        $data['vpc_ReturnURL']   = $this->getReturnUrl();
        
        ksort($data);

        $data['vpc_SecureHash']  = $this->calculateHash($data);
        
        return $data;
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    private function calculateHash($data)
    {
        $secureSecret = $this->getSecureHash();

        $hash = $secureSecret;

        foreach ($data as $k => $v) {
            $hash .= $v;
        }

        $hash = strtoupper(md5($hash));

        return $hash;
    }
}
