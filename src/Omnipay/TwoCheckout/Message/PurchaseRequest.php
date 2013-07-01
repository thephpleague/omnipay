<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * 2Checkout Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    public function getSecretWord()
    {
        return $this->getParameter('secretWord');
    }

    public function setSecretWord($value)
    {
        return $this->setParameter('secretWord', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['sid'] = $this->getAccountNumber();
        $data['cart_order_id'] = $this->getTransactionId();
        $data['total'] = $this->getAmount();
        $data['tco_currency'] = $this->getCurrency();
        $data['fixed'] = 'Y';
        $data['skip_landing'] = 1;
        $data['x_receipt_link_url'] = $this->getReturnUrl();

        if ($this->getCard()) {
            $data['card_holder_name'] = $this->getCard()->getName();
            $data['street_address'] = $this->getCard()->getAddress1();
            $data['street_address2'] = $this->getCard()->getAddress2();
            $data['city'] = $this->getCard()->getCity();
            $data['state'] = $this->getCard()->getState();
            $data['zip'] = $this->getCard()->getPostcode();
            $data['country'] = $this->getCard()->getCountry();
            $data['phone'] = $this->getCard()->getPhone();
            $data['email'] = $this->getCard()->getEmail();
        }

        if ($this->getTestMode()) {
            $data['demo'] = 'Y';
        }

        return $data;
    }

    public function send()
    {
        return $this->response = new PurchaseResponse($this, $this->getData());
    }
}
