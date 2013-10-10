<?php

namespace Omnipay\FirstDataConnect\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * First Data Connect Complete Authorize Request
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
            throw new InvalidResponseException("Callback hash does not match expected value");
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
        if (empty($storeId)) {
            throw new InvalidRequestException("storeId parameter missing, cannot process request");
        }

        $sharedSecret = $this->getSharedSecret();
        if (empty($sharedSecret)) {
            throw new InvalidRequestException("sharedSecret parameter missing, cannot process request");
        }

        $currency = $this->getCurrencyNumeric();
        if (empty($currency)) {
            throw new InvalidRequestException("currency parameter missing, cannot process request");
        }

        $stringToHash = $sharedSecret . $code . $amount . $currency . $dateTime . $storeId;
        $ascii = bin2hex($stringToHash);
        
        return sha1($ascii);
    }
}
