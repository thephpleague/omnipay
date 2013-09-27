<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\FirstDataConnect\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * First Data Connect Complete Authorize Request
 *
 * @todo check hash on return
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $theirHash = (string) $this->httpRequest->request->get('response_hash');
        $dateTime = (string) $this->httpRequest->request->get('txndatetime');
        $amount = (string) $this->httpRequest->request->get('chargetotal');
        $code = (string)$this->httpRequest->request->get('approval_code');
        $ourHash = $this->createResponseHash($amount, $dateTime, $code);
        if ($theirHash !== $ourHash) {
            echo $ourHash . '<br>' . $theirHash;
            throw new InvalidResponseException("Invalid callback password");
        }
        return $this->httpRequest->request->all();
    }

    public function send()
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getData());
    }

    /**
     * Generate a hash string that matches the format of the one returned by the payment gateway
     * @param string $amount
     * @param string $dateTime
     * @param string $code
     * @return string
     */
    public function createResponseHash($amount, $dateTime, $code)
    {
        $storeId = $this->getStoreId();
        $sharedSecret = $this->getSharedSecret();
        $currency = $this->getCurrencyNumeric();
        $stringToHash = $sharedSecret . $code . $amount . $currency . $dateTime . $storeId;
        $ascii = bin2hex($stringToHash);
        return sha1($ascii);
    }
}
