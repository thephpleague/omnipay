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
 * GoCardless Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = $this->getBaseData();

        $data['vpc_Amount']      = $this->getAmount();
        $data['vpc_MerchTxnRef'] = $this->generateNonce(40);
        $data['vpc_OrderInfo']   = $this->generateNonce(34);
        $data['vpc_ReturnURL']   = $this->getReturnUrl();
        
        $secure_secret = $this->getSecureHash();

        $md5HashData = $secure_secret.
            $data['vpc_AccessCode'] .
            $data['vpc_Amount'] .
            $data['vpc_Command'].
            $data['vpc_Locale'] .
            $data['vpc_MerchTxnRef'] .
            $data['vpc_Merchant'] .
            $data['vpc_OrderInfo'].
            $data['vpc_ReturnURL'] .
            $data['vpc_Version'];

        $data['vpc_SecureHash']  = strtoupper(md5($md5HashData));

        return $data;
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }

    /**
     * Generate a nonce for each request
     */
    protected function generateNonce($length = 34)
    {
        $nonce = '';
        for ($i = 0; $i < 64; $i++) {
            // append random ASCII character
            $nonce .= chr(mt_rand(33, 126));
        }

        $nonce = base64_encode($nonce);
        $nonce = substr($nonce, 0, $length);

        return $nonce;
    }
}
