<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
