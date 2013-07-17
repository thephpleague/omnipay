<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Realex\Message;

/**
 * Realex Redirect Complete Purchase Request
 */
class RedirectCompletePurchaseRequest extends RedirectPurchaseRequest
{
    public function getData()
    {
        $data = $this->httpRequest->request->all();

        $timestamp = isset($data['TIMESTAMP']) ? $data['TIMESTAMP'] : null;
        $orderId = isset($data['ORDER_ID']) ? $data['ORDER_ID'] : null;
        $result = isset($data['RESULT']) ? $data['RESULT'] : null;
        $message = isset($data['MESSAGE']) ? $data['MESSAGE'] : null;
        $pasRef = isset($data['PASREF']) ? $data['PASREF'] : null;
        $authCode = isset($data['AUTHCODE']) ? $data['AUTHCODE'] : null;
        $sha1Hash = isset($data['SHA1HASH']) ? $data['SHA1HASH'] : null;

        $baseHash = sha1("{$timestamp}.{$this->getUsername()}.{$orderId}.{$result}.{$message}.{$pasRef}.{$authCode}");

        if (sha1($baseHash . '.' . $this->getSecret()) == $sha1Hash) {
            return $data;
        }

        exit;
    }

    public function send()
    {
        return $this->response = new RedirectCompletePurchaseResponse($this, $this->getData());
    }
}
