<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Sage Pay Server Complete Authorize Request
 */
class ServerCompleteAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionId', 'transactionReference');

        $reference = json_decode($this->getTransactionReference(), true);

        // validate VPSSignature
        $signature = md5(
            $reference['VPSTxId'].
            $reference['VendorTxCode'].
            $this->httpRequest->request->get('Status').
            $this->httpRequest->request->get('TxAuthNo').
            $this->getVendor().
            $this->httpRequest->request->get('AVSCV2').
            $reference['SecurityKey'].
            $this->httpRequest->request->get('AddressResult').
            $this->httpRequest->request->get('PostCodeResult').
            $this->httpRequest->request->get('CV2Result').
            $this->httpRequest->request->get('GiftAid').
            $this->httpRequest->request->get('3DSecureStatus').
            $this->httpRequest->request->get('CAVV').
            $this->httpRequest->request->get('AddressStatus').
            $this->httpRequest->request->get('PayerStatus').
            $this->httpRequest->request->get('CardType').
            $this->httpRequest->request->get('Last4Digits')
        );

        if (strtolower($this->httpRequest->request->get('VPSSignature')) !== $signature) {
            throw new InvalidResponseException;
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new ServerCompleteAuthorizeResponse($this, $data);
    }
}
