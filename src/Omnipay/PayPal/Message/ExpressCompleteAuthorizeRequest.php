<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Express Complete Authorize Request
 */
class ExpressCompleteAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
        $data = $this->getBaseData('DoExpressCheckoutPayment');

        $this->validate('amount');

        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->action;
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmountDecimal();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_INVNUM'] = $this->getTransactionId();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();

        // loop cart items
        $i = 0;
        foreach ($this->getCart() as $cart)
        {
            $this->setAmount($cart->price);
            
            $data['L_PAYMENTREQUEST_0_QTY'.$i] = 1;
            $data['L_PAYMENTREQUEST_0_AMT'.$i] = $this->getAmountDecimal();
            $data['L_PAYMENTREQUEST_0_NAME'.$i] = $cart->title;
            $data['L_PAYMENTREQUEST_0_NUMBER'.$i] = $i;
            
            $i++;
        }
        
        $data['TOKEN'] = $this->httpRequest->query->get('token');
        $data['PAYERID'] = $this->httpRequest->query->get('PayerID');

        return $data;
    }
}
