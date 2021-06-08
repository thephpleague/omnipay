<?php

namespace Omnipay\Myfatoorah\Message;

class CompletePurchase extends AbstractRequest {

    public function getPaymentId() {
        return isset($_GET['paymentId']) ? $_GET['paymentId'] : '';
    }

    public function setPaymentId($value) {
        return $this->setParameter('paymentId', $value);
    }

    public function getData() {
        $data['Key']     = $this->getPaymentId();
        $data['KeyType'] = "paymentId";
        return $data;
    }

    public function getHttpMethod() {
        return 'POST';
    }

    public function getEndpoint() {
        $endpoint = $this->getTestMode() ? $this->sandboxEndpoint : $this->productionEndpoint;
        return $endpoint . '/v2/getpaymentstatus';
    }

}
