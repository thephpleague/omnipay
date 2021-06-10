<?php

namespace Omnipay\Myfatoorah\Message;

class RefundRequest extends AbstractRequest {

    public function getPaymentId() {
        return $this->getParameter('PaymentId');
    }

    public function setPaymentId($value) {
        return $this->setParameter('PaymentId', $value);
    }

    public function getAmount() {
        return $this->getParameter('Amount');
    }

    public function setAmount($value) {
        return $this->setParameter('Amount', $value);
    }
    public function getData() {
        $data = array(
            'KeyType'                 => 'PaymentId',
            'Key'                     => $this->getPaymentId(),
            'RefundChargeOnCustomer'  => false,
            'ServiceChargeOnCustomer' => false,
            'Amount'                  => $this->getAmount(),
        );
        return $data;
    }

    public function getHttpMethod() {
        return 'POST';
    }

    public function getEndpoint() {
        $endpoint = $this->getTestMode() ? $this->sandboxEndpoint : $this->productionEndpoint;
        return $endpoint . '/v2/MakeRefund';
    }

}
