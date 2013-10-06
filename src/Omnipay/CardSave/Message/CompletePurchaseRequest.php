<?php

namespace Omnipay\CardSave\Message;

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * CardSave Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $md = $this->httpRequest->request->get('MD');
        $paRes = $this->httpRequest->request->get('PaRes');
        if (empty($md) || empty($paRes)) {
            throw new InvalidResponseException;
        }

        $data = new SimpleXMLElement('<ThreeDSecureAuthentication/>');
        $data->addAttribute('xmlns', $this->namespace);
        $data->ThreeDSecureMessage->MerchantAuthentication['MerchantID'] = $this->getMerchantId();
        $data->ThreeDSecureMessage->MerchantAuthentication['Password'] = $this->getPassword();
        $data->ThreeDSecureMessage->ThreeDSecureInputData['CrossReference'] = $md;
        $data->ThreeDSecureMessage->ThreeDSecureInputData->PaRES = $paRes;

        return $data;
    }
}
