<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Create Credit Card Request
 */
class PxPostCreateCardRequest extends PxPostAuthorizeRequest
{
    public function getData()
    {
        $this->validate('card');
        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data->Amount = '1.00';
        $data->EnableAddBillCard = 1;
        $data->CardNumber = $this->getCard()->getNumber();
        $data->CardHolderName = $this->getCard()->getName();
        $data->DateExpiry = $this->getCard()->getExpiryDate('my');
        $data->Cvc2 = $this->getCard()->getCvv();

        return $data;
    }
}
