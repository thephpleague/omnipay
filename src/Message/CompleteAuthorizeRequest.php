<?php

namespace Omnipay\Myfatoorah\Message;

class CompleteAuthorizeRequest extends AbstractRequest {

    public function getData() {
        $data                       = array();
        $data['InvoiceValue']       = $this->getAmount();
        $data['CustomerReference']  = $this->getOrderRef();
        $data['DisplayCurrencyIso'] = $this->getCurrency();
        $data['CallBackUrl']        = $this->getCallBackUrl();
        $data['ErrorUrl']           = $this->getCallBackUrl();
        $data['CustomerName']       = $this->getFirstName() . ' ' . $this->getLastName();
        $data['CustomerEmail']      = $this->getEmail();
        $data['NotificationOption'] = "LNK";
        return $data;
    }

    public function getHttpMethod() {
        return 'POST';
    }

    public function getEndpoint() {
        $endpoint = $this->getTestMode() ? $this->sandboxEndpoint : $this->productionEndpoint;
        return $endpoint . '/v2/sendPayment';
    }

}
