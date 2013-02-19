<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

/**
 * PayPal Express Authorize Request
 */
class ExpressAuthorizeRequest extends AbstractRequest
{
    protected $solutionType;
    protected $landingPage;

    public function getSolutionType()
    {
        return $this->solutionType;
    }

    public function setSolutionType($value)
    {
        $this->solutionType = $value;

        return $this;
    }

    public function getLandingPage()
    {
        return $this->landingPage;
    }

    public function setLandingPage($value)
    {
        $this->landingPage = $value;

        return $this;
    }

    public function getData()
    {
        $data = $this->getBaseData('SetExpressCheckout');

        $this->validate(array('amount', 'returnUrl', 'cancelUrl'));

        $data['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Authorization';
        $data['PAYMENTREQUEST_0_AMT'] = $this->getAmountDecimal();
        $data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->getCurrency();
        $data['PAYMENTREQUEST_0_DESC'] = $this->getDescription();

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['NOSHIPPING'] = 1;
        $data['ALLOWNOTE'] = 0;
        $data['RETURNURL'] = $this->getReturnUrl();
        $data['CANCELURL'] = $this->getCancelUrl();

        if ($card = $this->getCard()) {
            $data['PAYMENTREQUEST_0_SHIPTONAME'] = $card->getName();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET'] = $card->getAddress1();
            $data['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $card->getAddress2();
            $data['PAYMENTREQUEST_0_SHIPTOCITY'] = $card->getCity();
            $data['PAYMENTREQUEST_0_SHIPTOSTATE'] = $card->getState();
            $data['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $card->getCountry();
            $data['PAYMENTREQUEST_0_SHIPTOZIP'] = $card->getPostcode();
            $data['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $card->getPhone();
            $data['EMAIL'] = $card->getEmail();
        }

        return $data;
    }

    public function createResponse($data)
    {
        return new ExpressAuthorizeResponse($data);
    }
}
